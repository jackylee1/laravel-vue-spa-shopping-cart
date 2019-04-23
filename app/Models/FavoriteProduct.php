<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\FavoriteProduct
 *
 * @property int $id
 * @property int $favorite_id
 * @property int $product_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FavoriteProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FavoriteProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FavoriteProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FavoriteProduct whereFavoriteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FavoriteProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FavoriteProduct whereProductId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Product $product
 */
class FavoriteProduct extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'favorite_id',
        'product_id',
    ];

    protected $casts = [
        'favorite_id' => 'integer',
        'product_id' => 'integer',
    ];

    protected $with = ['product'];

    public function product() {
        return $this->hasOne('App\Models\Product', 'id', 'product_id')->addSelect([
            'products.id',
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
