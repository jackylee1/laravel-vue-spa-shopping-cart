<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductInFilter
 *
 * @property int $id
 * @property int $product_id
 * @property int $filter_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter whereFilterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter whereProductId($value)
 * @mixin \Eloquent
 * @property int $category_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter whereCategoryId($value)
 * @property int|null $type_id
 * @property-read \App\Models\Category $category
 * @property-read \App\Models\Filter $filter
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter whereTypeId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductInFilterCategory[] $categories
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilter withCacheCooldownSeconds($seconds)
 */
class ProductInFilter extends Model
{
    use Cachable;

    protected $fillable = [
        'product_id',
        'type_id',
        'filter_id',
        'category_id'
    ];
    protected $casts = [
        'product_id' => 'integer',
        'type_id' => 'integer',
        'filter_id' => 'integer',
        'category_id' => 'integer'
    ];
    public $timestamps = false;

    protected $with = ['categories'];

    public function product() {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function filter() {
        return $this->hasOne('App\Models\Filter', 'id', 'filter_id');
    }

    public function category() {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }

    public function categories() {
        return $this->hasMany('App\Models\ProductInFilterCategory', 'product_in_filter_id', 'id');
    }

    protected function destroyModel($id) {
        ProductInFilter::find($id)->delete();
    }
}
