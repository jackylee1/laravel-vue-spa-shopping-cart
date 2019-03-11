<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PromotionalCode
 *
 * @property int $id
 * @property string $code
 * @property string $like_code
 * @property int $discount
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\UserPromotionalCode $userPromotionalCode
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode whereLikeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PromotionalCode withCacheCooldownSeconds($seconds)
 */
class PromotionalCode extends Model
{
    use Cachable;

    public function userPromotionalCode() {
        return $this->hasOne('App\Models\UserPromotionalCode',
                            'promotional_code_id', 'id');
    }

    public static function promotionalCodes() {
        $query = PromotionalCode::query();
        if (request()->get('q') !== null) {
            $like = getOnlyCharacters(request()->get('q'));
            $query->whereRaw('lower(like_code) like ?', ["%{$like}%"]);
        }

        if (request()->get('status') !== null && request()->get('status') !== 'all') {
            $query->where('status', request()->get('status'));
        }

        if (request()->get('user_id') !== null) {
            $query->whereHas('userPromotionalCode', function ($query) {
                $query->where('user_id', request()->get('user_id'));
            });
        }

        return $query->orderByDesc('created_at')->paginate(10);
    }

    protected function getModelByCode($code = null) {
        return PromotionalCode::where('code', $code)->first();
    }

    protected function workWithModel($model) {
        $model->code = request()->get('code');
        $model->like_code = getOnlyCharacters(request()->get('code'));
        $model->discount = request()->get('discount');
        $model->status = request()->get('status');
        $model->save();

        return $model;
    }

    protected function createModel() {
        return $this->workWithModel(new PromotionalCode());
    }

    protected function updateModel() {
        return $this->workWithModel(PromotionalCode::find(request()->get('id')));
    }

    protected function destroyModel($id) {
        PromotionalCode::find($id)->delete();
    }
}
