<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductInFilterTree
 *
 * @property int $id
 * @property int $product_in_filter_id
 * @property int $filter_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterTree newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterTree newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterTree query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterTree whereFilterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterTree whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterTree whereProductInFilterId($value)
 * @mixin \Eloquent
 */
class ProductInFilterTree extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'product_in_filter_id',
        'filter_id',
    ];
    protected $casts = [
        'product_in_filter_id' => 'integer',
        'filter_id' => 'integer',
    ];
}
