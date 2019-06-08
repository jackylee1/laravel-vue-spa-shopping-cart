<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterTree disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductInFilterTree withCacheCooldownSeconds($seconds)
 */
class ProductInFilterTree extends Model
{
    use Cachable;

    public $timestamps = false;
    protected $fillable = [
        'product_in_filter_id',
        'filter_id',
    ];
    protected $casts = [
        'product_in_filter_id' => 'integer',
        'filter_id' => 'integer',
    ];

    public function productInFilter() {
        return $this->hasOne('App\Models\ProductInFilter', 'id', 'product_in_filter_id');
    }

    public static function findByFilterId($filter_id, $type_id, $category_id, $check_in_category_relation = false) {
        return ProductInFilterTree::where('filter_id', $filter_id)->whereHas('productInFilter',
            function ($query) use ($type_id, $category_id, $check_in_category_relation) {
            $where = [
                ['type_id', $type_id],
            ];
            if (!$check_in_category_relation) {
                $where[] = ['category_id', $category_id];
            }
            else {
                $query->whereHas('categories', function ($query) use ($category_id) {
                    $query->where('category_id', $category_id);
                });
            }
            $query->where($where);
        })->first();
    }
}
