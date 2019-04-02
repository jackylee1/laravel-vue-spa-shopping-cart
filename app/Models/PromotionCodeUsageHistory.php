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
 * @property int $id
 * @property int|null $user_id
 * @property int $promotional_code_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory wherePromotionalCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionCodeUsageHistory withCacheCooldownSeconds($seconds)
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
