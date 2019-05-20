<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderProduct
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property float $price
 * @property float|null $discount_price
 * @property int $quantity
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereQuantity($value)
 * @mixin \Eloquent
 * @property int $product_available_id
 * @property float $product_price
 * @property float|null $product_discount_price
 * @property-read \App\Models\ProductAvailable $available
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereProductAvailableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereProductDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct whereProductPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProduct withCacheCooldownSeconds($seconds)
 */
class OrderProduct extends Model
{
    use Cachable;

    public $timestamps = false;
    protected $fillable = [
        'order_id',
        'product_id',
        'product_available_id',
        'price',
        'discount_price',
        'product_price',
        'product_discount_price',
        'quantity'
    ];

    protected $casts = [
        'order_id' => 'integer',
        'product_id' => 'integer',
        'product_available_id' => 'integer',
        'price' => 'float',
        'product_price' => 'float',
        'product_discount_price' => 'float',
        'discount_price' => 'float',
        'quantity' => 'integer'
    ];

    protected $with = [
        'product', 'available'
    ];

    public function product() {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function available() {
        return $this->hasOne('App\Models\ProductAvailable', 'id', 'product_available_id');
    }
}
