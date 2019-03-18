<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserGroup
 *
 * @property int $id
 * @property string $name
 * @property string $like_name
 * @property int $discount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserInGroup[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup whereLikeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserGroup withCacheCooldownSeconds($seconds)
 */
class UserGroup extends Model
{
    use Cachable;

    protected $fillable = ['name', 'like_name', 'discount'];

    public function users() {
        return $this->hasMany('App\Models\UserInGroup');
    }

    protected function userGroups() {
        return UserGroup::orderByDesc('id')->get();
    }

    public static function userGroup($id) {
        return UserGroup::find($id);
    }

    protected function userActionHandler() {
        $operation = 'removed';
        $where = [
            ['user_id', request()->get('user_id')],
            ['user_group_id', request()->get('user_group_id')]
        ];
        $user_group = UserGroup::find(request()->get('user_group_id'));
        $select = $user_group->users()->where($where)->first();
        if ($select !== null) {
            $user_group->users()->where($where)->delete();
        }
        else {
            $operation = 'added';
            $user_group->users()->create([
                'user_id' => request()->get('user_id'),
                'user_group_id' => request()->get('user_group_id')
            ]);
        }
        UserInGroup::deleteByUserId(request()->get('user_id'), request()->get('user_group_id'));
        return [
            'operation' => $operation,
            'user_group' => $user_group
        ];
    }

    protected function workWithModel($model) {
        $model->name = request()->get('name');
        $model->like_name = getOnlyCharacters(request()->get('name'));
        $model->discount = request()->get('discount');

        $model->save();

        return $model;
    }

    protected function createModel() {
        return $this->workWithModel(new UserGroup());
    }

    protected function updateModel() {
        return $this->workWithModel(UserGroup::find(request()->get('id')));
    }

    protected function destroyModel($id) {
        UserGroup::find($id)->delete();
    }
}
