<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserPromotionalCode
 *
 * @property int $id
 * @property int $user_id
 * @property int $promotional_code_id
 * @property int $send_status_to_email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PromotionalCode $promotionalCode
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode wherePromotionalCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode whereSendStatusToEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserPromotionalCode withCacheCooldownSeconds($seconds)
 */
class UserPromotionalCode extends Model
{
    use Cachable;

    protected $fillable = ['user_id', 'promotional_code_id', 'send_status_to_email'];

    public function promotionalCode() {
        return $this->hasOne('App\Models\PromotionalCode', 'id', 'promotional_code_id');
    }

    public static function destroyByPromotionalCodeId($id, $user_id = null) {
        $query = UserPromotionalCode::query();
        if ($user_id !== null) {
            $query->where('user_id', '<>', $user_id);
        }
        $query->where('promotional_code_id', $id)->delete();
    }
}
