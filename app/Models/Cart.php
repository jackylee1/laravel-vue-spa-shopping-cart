<?php

namespace App\Models;

use App\Tools\KeyTool;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cart
 *
 * @property int $id
 * @property string $key
 * @property int|null $user_id
 * @property int|null $user_promotional_code_id
 * @property string|null $user_name
 * @property string|null $user_surname
 * @property string|null $user_patronymic
 * @property string|null $phone
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUserPatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUserPromotionalCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUserSurname($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CartProduct[] $products
 * @property int|null $order_payment_method_id
 * @property int|null $delivery
 * @property string|null $area_id
 * @property string|null $city_id
 * @property string|null $warehouse_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereOrderPaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereWarehouseId($value)
 * @property string|null $note
 * @property-read \App\Models\NovaPoshtaArea $npArea
 * @property-read \App\Models\NovaPoshtaCity $npCity
 * @property-read \App\Models\NovaPoshtaWarehouse $npWarehouse
 * @property-read \App\Models\OrderPaymentMethod $paymentMethod
 * @property-read \App\Models\PromotionalCode $promotionalCode
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart withCacheCooldownSeconds($seconds)
 */
class Cart extends Model
{
    use Cachable;

    protected $fillable = [
        'key',
        'user_id',
        'user_promotional_code_id',
        'order_payment_method_id',
        'user_name',
        'user_surname',
        'phone',
        'email',
        'delivery',
        'area_id',
        'city_id',
        'warehouse_id',
        'note'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'user_promotional_code_id' => 'integer',
        'order_payment_method_id' => 'integer',
        'delivery' => 'integer',
    ];

    protected $with = [
        'products',
        'npArea',
        'npCity',
        'npWarehouse',
        'paymentMethod',
        'promotionalCode'
    ];

    public function promotionalCode() {
        return $this->hasOne('App\Models\PromotionalCode', 'id', 'user_promotional_code_id');
    }

    public function products() {
        return $this->hasMany('App\Models\CartProduct', 'cart_id', 'id')->orderByDesc('id');
    }

    public function npArea() {
        return $this->hasOne('App\Models\NovaPoshtaArea', 'ref', 'area_id');
    }

    public function npCity() {
        return $this->hasOne('App\Models\NovaPoshtaCity', 'ref', 'city_id');
    }

    public function npWarehouse() {
        return $this->hasOne('App\Models\NovaPoshtaWarehouse', 'ref', 'warehouse_id');
    }

    public function paymentMethod() {
        return $this->hasOne('App\Models\OrderPaymentMethod', 'id', 'order_payment_method_id');
    }

    private static function getWhereQuery() {
        return [
            'key' => KeyTool::getKeyUser('cart_key'),
            'user_id' => (auth()->check()) ? auth()->user()->id : null
        ];
    }

    public static function getByKey($key) {
        return Cart::where('key', $key)->first();
    }

    public static function firstOrCreateModel() {
        $cart = Cart::firstOrCreate(self::getWhereQuery());

        return $cart;
    }

    public static function getItem() {
        $cart = self::firstOrCreateModel();

        return $cart->fresh();
    }

    protected function addProduct() {
        $cart = self::getItem();

        return $cart->products()->create([
            'product_id' => request()->get('product_id'),
            'product_available_id' => request()->get('product_available_id'),
            'quantity' => request()->get('quantity'),
        ])->fresh();
    }

    protected function deleteProduct() {
        self::getItem()->products()->where('id', request()->get('id'))->delete();
    }

    protected function updateQuantityProduct() {
        $cart_product = self::getItem()->products()->where('id', request()->get('id'))->first();
        $cart_product->update([
            'quantity' => request()->get('quantity')
        ]);

        return $cart_product;
    }

    protected function updateModel() {
        $model = self::firstOrCreateModel();

        if ($model->email !== null) {
            Subscribe::firstOrCreateModel($model->email);
        }

        $data = [
            'user_id' => (auth()->check()) ? auth()->user()->id : null,
            'order_payment_method_id' => request()->get('order_payment_method_id'),
            'user_name' => request()->get('user_name'),
            'user_surname' => request()->get('user_surname'),
            'phone' => request()->get('phone'),
            'email' => request()->get('email'),
            'delivery' => request()->get('delivery'),
            'area_id' => request()->get('area_id'),
            'city_id' => request()->get('city_id'),
            'warehouse_id' => request()->get('warehouse_id'),
            'note' => request()->get('note')
        ];

        $model->update($data);

        return $model->fresh();
    }

    protected function updatePromotionalCode($promotional_code_id) {
        $cart = self::firstOrCreateModel();

        $cart->user_promotional_code_id = $promotional_code_id;

        $cart->save();

        return $cart->fresh();
    }
}
