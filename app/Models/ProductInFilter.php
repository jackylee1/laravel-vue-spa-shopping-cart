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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductInFilterTree[] $filters
 * @property-read \App\Models\ProductAvailableFilter $availableFilter
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

    protected $with = ['categories', 'filters'];

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

    public function filters() {
        return $this->hasMany('App\Models\ProductInFilterTree', 'product_in_filter_id', 'id');
    }

    public function availableFilter() {
        return $this->hasOne('App\Models\ProductAvailableFilter', 'filter_id', 'filter_id');
    }

    protected function destroyModel($id) {
        ProductInFilter::find($id)->delete();
    }

    public static function getProductIdsByFilters($filters, $type_id = null, $category_id = null) {
        $query = ProductInFilter::query();

        if ($type_id !== null) {
            $query->where('type_id', $type_id);
        }
        if ($category_id !== null) {
            $id_categories = Category::getChildrenCategories($category_id);
            $id_categories[] = $category_id;
            $query->whereHas('categories', function ($query) use ($id_categories) {
                $query->whereIn('category_id', $id_categories);
            });
        }

        $query->whereIn('filter_id', arrayFlatten($filters->toArray()));

        $products = $query->setEagerLoads([])->with('filters')->get();

        $id_products = [];

        $products->groupBy('product_id')->each(function ($product, $key) use ($filters, &$id_products) {
            $id_filters = [];
            $product->each(function ($item) use (&$id_filters) {
                $id_filters[] = $item->filter_id;
                $item->filters->each(function ($item) use (&$id_filters) {
                    $id_filters[] = $item->filter_id;
                });
            });
            $id_filters = array_unique($id_filters);

            $check_exist_filters = [];
            $filters->each(function ($filter) use (&$check_exist_filters, $id_filters) {
                if (is_array($filter)) {
                    $check_exist_filters[] = (count(array_intersect($filter, $id_filters)) > 0) ? true : false;
                }
            });

            if (!in_array(false, $check_exist_filters)) {
                $id_products[] = $key;
            }
        });

        return $id_products;
    }

    public static function getItemsByTypeAndCategory($type_id, $category_id) {
        return ProductInFilter::where([
            ['type_id', $type_id],
            ['category_id', $category_id]
        ])->get();
    }
}
