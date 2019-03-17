<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $user_name
 * @property string|null $user_surname
 * @property string|null $user_patronymic
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $note
 * @property int|null $order_status_id
 * @property int|null $delivery_method
 * @property int|null $payment_method_id
 * @property int|null $promotion_code_id
 * @property float|null $total_price
 * @property float|null $total_discount_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereDeliveryMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereOrderStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order wherePromotionCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereTotalDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserPatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserSurname($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'user_surname',
        'user_patronymic',
        'phone',
        'email',
        'note',
        'order_status_id',
        'delivery_method',
        'order_payment_method_id',
        'promotional_code_id',
        'total_price',
        'total_discount_price'
    ];
    protected $casts = [
        'user_id' => 'integer',
        'order_status_id' => 'integer',
        'delivery_method' => 'integer',
        'order_payment_method_id' => 'integer',
        'promotional_code_id' => 'integer',
        'total_price' => 'float',
        'total_discount_price' => 'float'
    ];

    protected $with = [
        'products', 'historyStatuses'
    ];

    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function status() {
        return $this->hasOne('App\Models\OrderStatus', 'id', 'order_status_id');
    }

    public function paymentMethod() {
        return $this->hasOne('App\Models\OrderPaymentMethod', 'id', 'order_payment_method_id');
    }

    public function promotionCode() {
        return $this->hasOne('App\Models\PromotionalCode', 'id', 'promotional_code_id');
    }

    public function products() {
        return $this->hasMany('App\Models\OrderProduct', 'order_id', 'id');
    }

    public function historyStatuses() {
        return $this->hasMany('App\Models\OrderHistoryStatus', 'order_id', 'id');
    }

    protected function createModel($note = null) {
        $order = new $this;

        $default_order_status = OrderStatus::getDefault();
        if ($default_order_status === null) {
            $default_order_status = OrderStatus::createDefaultStatus();
        }
        $order->order_status_id = $default_order_status->id;
        $order->note = $note;
        $order->save();

        $order->historyStatuses()->create(['order_status_id' => $default_order_status->id]);

        return $order->fresh();
    }

    protected function updateModel() {
        $order = Order::find(request()->get('id'));

        $order->user_id = request()->get('user_id');
        $order->user_name = request()->get('user_name');
        $order->user_surname = request()->get('user_surname');
        $order->user_patronymic = request()->get('user_patronymic');
        $order->phone = request()->get('phone');
        $order->email = request()->get('email');
        $order->note = request()->get('note');
        if ($order->order_status_id != request()->get('order_status_id')) {
            $order->order_status_id = request()->get('order_status_id');
            $order_history_status = $order->historyStatuses()->create([
                'order_status_id' => request()->get('order_status_id')
            ]);
            $order->historyStatuses->push($order_history_status);
        }
        $order->order_payment_method_id = request()->get('order_payment_method_id');

        $order->save();

        return $order;
    }

    protected function getOrder($id) {
        return $this::find($id);
    }

    protected function orders() {
        $query = Order::query();

        return $query->orderBy('created_at', 'asc')->paginate();
    }

    protected function addProduct() {
        $order = Order::find(request()->get('order_id'));
        $product = Product::getProduct(request()->get('product_id'));
        $available = $product->available()->where('id', request()->get('product_available_id'))->first();
        $available->update([
            'quantity' => $available->quantity - request()->get('quantity')
        ]);

        $price = $product->price  * request()->get('quantity');
        $discount_price = $product->discount_price * request()->get('quantity');

        $order_product = $order->products()->create([
            'product_id' => $product->id,
            'product_available_id' => request()->get('product_available_id'),
            'price' => $price,
            'discount_price' => $discount_price,
            'product_price' => $product->price,
            'product_discount_price' => $product->discount_price,
            'quantity' => request()->get('quantity')
        ])->fresh();

        $order->products->push($order_product);

        $order->update([
            'total_price' => $order->total_price + $price,
        ]);

        return $order;
    }

    protected function deleteProduct() {
        $order = Order::find(request()->get('order_id'));
        $order_product = $order->products->where('id', request()->get('order_product_id'))->first();
        $available = $order_product->available()->where('id', $order_product->product_available_id)->first();
        $available->update([
            'quantity' => $order_product->quantity + $available->quantity
        ]);
        $order->products()->where('id', request()->get('order_product_id'))->delete();
        $order->total_price = $order->total_price - $order_product->price;
        $order->save();

        return [
            'order' => $order->fresh(),
            'order_product' => $order_product
        ];
    }

    protected function deleteStatus() {
        $order = Order::find(request()->get('order_id'));
        $order->historyStatuses()->where('id', request()->get('order_status_id'))->delete();

        return $order->fresh();
    }

    protected function getFirstStatus() {
        return Order::find(request()->get('order_id'))->historyStatuses()->orderBy('id', 'asc')->first();
    }

    protected function destroyModel($id) {
        Order::find($id)->delete();
    }
}
