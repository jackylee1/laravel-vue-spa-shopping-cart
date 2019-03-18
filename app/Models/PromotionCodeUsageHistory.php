<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PromotionCodeUsageHistory
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory query()
 * @mixin \Eloquent
 */
class PromotionCodeUsageHistory extends Model
{
    use Cachable;

    protected $table = 'promotional_code_usage_histories';

    protected $fillable = [
        'user_id',
        'promotional_code_id'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'promotional_code_id' => 'integer'
    ];
}
