<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductInFilterCategory
 *
 * @property int $id
 * @property int $product_in_filter_id
 * @property int $category_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterCategory whereProductInFilterId($value)
 * @mixin \Eloquent
 */
class ProductInFilterCategory extends Model
{
    use Cachable;

    public $timestamps = false;
    protected $fillable = [
        'product_in_filter_id',
        'category_id'
    ];
    protected $casts = [
        'product_in_filter_id' => 'integer',
        'category_id' => 'integer'
    ];
}
