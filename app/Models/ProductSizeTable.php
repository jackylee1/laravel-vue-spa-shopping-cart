<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductSizeTable
 *
 * @property int $id
 * @property int $product_id
 * @property int $size_table_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSizeTable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSizeTable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSizeTable query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSizeTable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSizeTable whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSizeTable whereSizeTableId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSizeTable disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSizeTable withCacheCooldownSeconds($seconds)
 */
class ProductSizeTable extends Model
{
    use Cachable;

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'size_table_id'
    ];

    protected $casts = [
        'product_id' => 'integer',
        'size_table_id' => 'integer'
    ];
}
