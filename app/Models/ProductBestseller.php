<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductBestseller
 *
 * @property int $id
 * @property int $product_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductBestseller newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductBestseller newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductBestseller query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductBestseller whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductBestseller whereProductId($value)
 * @mixin \Eloquent
 * @property int|null $quantity
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductBestseller whereQuantity($value)
 */
class ProductBestseller extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'quantity'
    ];

    protected $casts = [
        'product_id' => 'integer',
        'quantity' => 'integer'
    ];

    public static function getProducts($limit = 15) {
        $products = ProductBestseller::orderByDesc('quantity')->limit($limit)->get();
        $ids = $products->map(function ($product) {
            return $product->product_id;
        });

        return $ids;
    }
}
