<?php

namespace App;

use App\Models\UserInGroup;
use App\Models\UserPromotionalCode;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $status
 * @property string|null $description
 * @property int $reliability
 * @property string $like_name
 * @property string $like_email
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\UserInGroup $group
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserPromotionalCode[] $promotionalCodes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLikeEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLikeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereReliability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable,
        Cachable;

    protected $fillable = [
        'status',
        'description',
        'user_name',
        'user_surname',
        'user_patronymic',
        'discount',
        'discount',
        'email',
        'phone',
        'password',
        'reliability',
        'discount',
        'group_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $with = ['group', 'promotionalCodes'];

    public function group() {
        return $this->hasOne('App\Models\UserInGroup');
    }

    public function promotionalCodes() {
        return $this->hasMany('App\Models\UserPromotionalCode');
    }

    public function promotionalCodeUsage() {
        return $this->hasMany('App\Models\PromotionCodeUsageHistory', 'user_id', 'id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected function getUsers() {
        $query = User::query();

        if (request()->get('q') !== null) {
            $like = prepareForLike(request()->get('q'));
            $query->whereRaw('lower(user_name) like ?', ["%{$like}%"])
                ->orWhereRaw('lower(user_surname) like ?', ["%{$like}%"])
                ->orWhereRaw('lower(user_patronymic) like ?', ["%{$like}%"])
                ->orWhereRaw('lower(email) like ?', ["%{$like}%"]);
        }

        if (request()->get('status') !== null && request()->get('status') !== 'all') {
            $query->where('status', request()->get('status'));
        }

        return $query->orderByDesc('id')->with([
            'group','promotionalCodes'
        ])->paginate(10);
    }

    private function workWithModel($model) {
        $model->user_name = request()->get('user_name');
        $model->user_surname = request()->get('user_surname');
        $model->user_patronymic = request()->get('user_patronymic');
        $model->email = request()->get('email');
        $model->phone = request()->get('phone');
        if (request()->get('id') !== 1) {
            $model->status = request()->get('status');
        }
        $model->description = request()->get('description');
        $model->reliability = request()->get('reliability');
        if (request()->get('password') !== null) {
            $model->password = \Hash::make(request()->get('password'));
        }
        $model->discount = request()->get('discount');

        $model->save();

        if (request()->get('group_id') !== null) {
            if (request()->get('id') !== null) {
                UserInGroup::deleteByUserId(request()->get('id'));
            }
            $model->group()->create([
                'user_group_id' => request()->get('group_id')
            ]);
        }

        return $model;
    }

    protected function createModel() {
        return $this->workWithModel(new User())->fresh();
    }

    protected function updateModel() {
        return $this->workWithModel(User::find(request()->get('id')));
    }

    protected function destroyModel($id) {
        User::find($id)->delete();
    }

    public static function getUser($id) {
        return User::with([
            'group',
            'promotionalCodes'
        ])->find($id);
    }

    private function destroyByPromotionalCodeId() {
        UserPromotionalCode::destroyByPromotionalCodeId(request()->get('promotional_code_id'));
    }

    protected function handlePromotionalCode() {
        $operation = 'add';
        $model = null;

        $user = User::find(request()->get('user_id'));

        if ($user->promotionalCodes->where('promotional_code_id', request()->get('promotional_code_id'))->count() > 0) {
            $operation = 'remove';
            $this->destroyByPromotionalCodeId();
        }
        else {
            $this->destroyByPromotionalCodeId();
            $model = $user->promotionalCodes()->create([
                'promotional_code_id' => request()->get('promotional_code_id')
            ]);
        }

        return [
            'operation' => $operation,
            'model' => $model
        ];
    }

    protected function havePromotionalCode() {
        return User::whereHas('promotionalCodes', function ($query) {
            $query->where('promotional_code_id', request()->get('promotional_code_id'));
            $query->with(['promotionalCode']);
        })->find(request()->get('user_id'));
    }

    protected function getPromotionalCodes() {
        return User::find(request()->get('user_id'))->promotionalCodes()->get();
    }
}
