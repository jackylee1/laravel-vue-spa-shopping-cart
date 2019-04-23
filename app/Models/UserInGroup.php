<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserInGroup
 *
 * @property int $id
 * @property int $user_id
 * @property int $user_group_id
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInGroup whereUserGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInGroup whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInGroup disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInGroup withCacheCooldownSeconds($seconds)
 * @property-read \App\Models\UserGroup $userGroup
 */
class UserInGroup extends Model
{
    use Cachable;

    public $timestamps = false;
    protected $fillable = ['user_id', 'user_group_id'];

    protected $with = ['userGroup'];

    public function userGroup() {
        return $this->hasOne('App\Models\UserGroup', 'id', 'user_group_id');
    }
    
    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public static function getUsers($id) {
        return UserInGroup::select()->where('user_group_id', $id)->with(['user'])->paginate(10);
    }

    public static function deleteByUserId($user_id, $user_group_id = null) {
        $query = UserInGroup::query();
        if ($user_group_id !== null) {
            $query->where('user_group_id', '<>', $user_group_id);
        }
        $query->where('user_id', $user_id)->delete();
    }
}
