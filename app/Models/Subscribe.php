<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Subscribe
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscribe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscribe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscribe query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $email
 * @property int $read_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscribe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscribe whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscribe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscribe whereReadStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscribe whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscribe disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Subscribe withCacheCooldownSeconds($seconds)
 */
class Subscribe extends Model
{
    use Cachable;

    protected $fillable = [
        'email',
        'read_status'
    ];
    protected $casts = [
        'read_status' => 'integer'
    ];

    protected function getSubscribe($id) {
        return Subscribe::find($id);
    }

    public static function getSubscribeByEmail($email) {
        return Subscribe::where('email', $email)->first();
    }

    private function workWithModel($model) {
        $model->email = request()->get('email');
        $model->save();

        return $model;
    }

    protected function createModel() {
        return $this->workWithModel(new Subscribe());
    }

    public static function firstOrCreateModel($email) {
        return Subscribe::firstOrCreate([
            'email' => $email
        ]);
    }

    protected function updateModel($id) {
        return $this->workWithModel($this->getSubscribe($id));
    }

    protected function updateReadStatus($id) {
        $this->getSubscribe($id)->update([
            'read_status' => true
        ]);
    }

    protected function getSubscribes() {
        $query = Subscribe::query();

        if (request()->filled('q')) {
            $query->where('email', 'like', '%'. request()->get('q') .'%');
        }

        return $query->orderByDesc('created_at')->paginate(10);
    }

    public static function newSubscribes() {
        return Subscribe::where('read_status', false)->count();
    }

    protected function destroyModel($id) {
        $this->getSubscribe($id)->delete();
    }
}
