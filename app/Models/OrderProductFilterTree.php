<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderProductFilterTree
 *
 * @property int $id
 * @property int $order_product_id
 * @property int $filter_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProductFilterTree newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProductFilterTree newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProductFilterTree query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProductFilterTree whereFilterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProductFilterTree whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderProductFilterTree whereOrderProductId($value)
 * @mixin \Eloquent
 */
class OrderProductFilterTree extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'order_product_id',
        'filter_id'
    ];
    protected $casts = [
        'order_product_id' => 'integer',
        'filter_id' => 'integer'
    ];
}
