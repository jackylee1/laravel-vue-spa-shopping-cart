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

        $order->update([
            'user_name' => request()->get('user_name'),
            'user_surname' => request()->get('user_surname'),
            'user_patronymic' => request()->get('user_patronymic'),
            'phone' => request()->get('phone'),
            'email' => request()->get('email'),
            'note' => request()->get('note'),
        ]);

        return $order;
    }

    protected function getOrder($id) {
        return $this::find($id);
    }

    protected function orders() {
        $query = Order::query();

        return $query->orderBy('created_at', 'asc')->paginate();
    }

    protected function destroyModel($id) {
        Order::find($id)->delete();
    }
}
