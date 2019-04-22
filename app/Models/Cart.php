<?php

namespace App\Models;

use App\Tools\KeyTool;
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
 */
class Cart extends Model
{
    protected $fillable = [
        'key',
        'user_id',
        'user_promotional_id',
        'user_name',
        'user_surname',
        'user_patronymic',
        'phone',
        'email'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'user_promotional_id' => 'integer',
    ];

    protected $with = ['products'];

    public function products() {
        return $this->hasMany('App\Models\CartProduct', 'cart_id', 'id')->orderByDesc('id');
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
        return Cart::firstOrCreate(self::getWhereQuery());
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
}
