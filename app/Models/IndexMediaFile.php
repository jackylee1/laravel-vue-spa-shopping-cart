<?php

namespace App\Models;

use App\Tools\File;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\IndexMediaFile
 *
 * @property int $id
 * @property int $sorting_order
 * @property string|null $video
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IndexMediaFile disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IndexMediaFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IndexMediaFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IndexMediaFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IndexMediaFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IndexMediaFile whereImageOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IndexMediaFile whereImagePreview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IndexMediaFile whereSortingOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IndexMediaFile whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IndexMediaFile whereVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IndexMediaFile withCacheCooldownSeconds($seconds)
 * @mixin \Eloquent
 */
class IndexMediaFile extends Model
{
    use Cachable;

    public $timestamps = false;

    protected $fillable = [
        'sorting_order',
        'video'
    ];

    protected $casts = [
        'sorting_order' => 'integer',
    ];

    public static function getItems() {
        return IndexMediaFile::orderBy('sorting_order', 'asc')->get();
    }

    protected function getItem($id) {
        return IndexMediaFile::find($id);
    }

    private function workWithModel($model) {
        $model->video = request()->get('video');
        $model->sorting_order = (request()->filled('sorting_order')) ? request()->get('sorting_order') : 0;
        $model->save();

        return $model;
    }

    protected function createModel() {
        return $this->workWithModel(new IndexMediaFile());
    }

    protected function updateModel() {
        return $this->workWithModel(IndexMediaFile::find(request()->get('id')));
    }

    protected function destroyModel($id) {
        $model = IndexMediaFile::find($id);
        $model->delete();
    }
}
