<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderPromotionalCodeUsedCash
 *
 * @property int $id
 * @property int $order_id
 * @property int $promotional_code_id
 * @property int $cash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPromotionalCodeUsedCash newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPromotionalCodeUsedCash newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPromotionalCodeUsedCash query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPromotionalCodeUsedCash whereCash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPromotionalCodeUsedCash whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPromotionalCodeUsedCash whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPromotionalCodeUsedCash whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPromotionalCodeUsedCash wherePromotionalCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPromotionalCodeUsedCash whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderPromotionalCodeUsedCash extends Model
{
    protected $fillable = [
        'order_id',
        'promotional_code_id',
        'cash'
    ];

    protected $casts = [
        'order_id' => 'integer',
        'promotional_code_id' => 'integer',
        'cash' => 'integer'
    ];
}
