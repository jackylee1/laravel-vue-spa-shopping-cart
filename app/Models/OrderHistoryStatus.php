<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderHistoryStatus
 *
 * @property int $id
 * @property int $order_id
 * @property int $order_status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus whereOrderStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderHistoryStatus extends Model
{
    protected $fillable = [
        'order_id',
        'order_status_id'
    ];
    protected $casts = [
        'order_id' => 'integer',
        'order_status_id' => 'integer'
    ];
}
