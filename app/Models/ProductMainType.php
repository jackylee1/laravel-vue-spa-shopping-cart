<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductMainType
 *
 * @property int $id
 * @property int $product_id
 * @property int $type_id
 * @property array $category_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductMainType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductMainType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductMainType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductMainType whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductMainType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductMainType whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductMainType whereTypeId($value)
 * @mixin \Eloquent
 */
class ProductMainType extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'type_id',
        'category_id'
    ];
    protected $casts = [
        'product_id' => 'integer',
        'type_id' => 'integer',
        'category_id' => 'array'
    ];
}
