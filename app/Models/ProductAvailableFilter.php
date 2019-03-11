<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductAvailableFilter
 *
 * @property int $id
 * @property int $product_available_id
 * @property int $filter_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailableFilter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailableFilter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailableFilter query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailableFilter whereFilterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailableFilter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailableFilter whereProductAvailableId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailableFilter disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailableFilter withCacheCooldownSeconds($seconds)
 */
class ProductAvailableFilter extends Model
{
    use Cachable;

    public $timestamps = false;
    protected $fillable = [
        'product_available_id',
        'filter_id'
    ];
    protected $casts = [
        'product_available_id' => 'integer',
        'filter_id' => 'integer'
    ];
}
