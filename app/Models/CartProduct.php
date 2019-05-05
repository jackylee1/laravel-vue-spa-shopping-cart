<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\CartProduct
 *
 * @property int $id
 * @property int $product_id
 * @property int $product_available_id
 * @property int $quantity
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct whereProductAvailableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct whereQuantity($value)
 * @mixin \Eloquent
 * @property int $cart_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct whereCartId($value)
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartProduct withCacheCooldownSeconds($seconds)
 */
class CartProduct extends Model
{
    use Cachable;

    public $timestamps = false;

    protected $fillable = [
        'cart_id',
        'product_id',
        'product_available_id',
        'quantity'
    ];

    protected $casts = [
        'cart_id' => 'integer',
        'product_id' => 'integer',
        'product_available_id' => 'integer',
        'quantity' => 'integer'
    ];

    protected $with = ['product'];

    public function product() {
        return $this->hasOne('App\Models\Product', 'id', 'product_id')->addSelect([
            'products.id',
            'products.article',
            'products.slug',
            'products.name',
            'products.price',
            'products.discount_price',
            'products.discount_start',
            'products.discount_end',
            'products.main_image',
            'products.status',
            'products.date_inclusion',
            DB::raw('IF(discount_price IS NOT NULL, discount_price, price) as current_price')
        ])->activeForPublic();
    }
}
