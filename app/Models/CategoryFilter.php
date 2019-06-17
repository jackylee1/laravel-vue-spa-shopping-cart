<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CategoryFilter
 *
 * @property int $id
 * @property int $category_id
 * @property int $filter_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryFilter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryFilter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryFilter query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryFilter whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryFilter whereFilterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryFilter whereId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryFilter disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryFilter withCacheCooldownSeconds($seconds)
 */
class CategoryFilter extends Model
{
    use Cachable;
    
    protected $fillable = ['category_id', 'filter_id'];
    public $timestamps = false;

    public function productInFiltersTree() {
        return $this->hasOne('App\Models\ProductInFilterTree', 'filter_id', 'filter_id');
    }

    public function filter() {
        return $this->hasOne('App\Models\Filter', 'id', 'filter_id');
    }
}
