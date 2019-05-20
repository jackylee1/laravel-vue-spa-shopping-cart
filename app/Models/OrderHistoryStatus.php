<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderHistoryStatus
 *
 * @property int $id
 * @property int $order_id
 * @property int $order_status_id
 * @property int $send_status
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
 * @property-read \App\Models\OrderStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus whereSendStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderHistoryStatus withCacheCooldownSeconds($seconds)
 */
class OrderHistoryStatus extends Model
{
    use Cachable;

    protected $fillable = [
        'order_id',
        'order_status_id',
        'send_status'
    ];
    protected $casts = [
        'order_id' => 'integer',
        'order_status_id' => 'integer',
        'send_status' => 'integer'
    ];

    protected $with = [
        'status'
    ];

    public function status() {
        return $this->hasOne('App\Models\OrderStatus', 'id', 'order_status_id');
    }
}
