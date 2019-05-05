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
 * @property int $type
 * @property string|null $image_origin
 * @property string|null $image_preview
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

    public $path_image = 'public/images/index_files/';

    protected $fillable = [
        'sorting_order',
        'type',
        'image_origin',
        'image_preview',
        'video'
    ];

    protected $casts = [
        'sorting_order' => 'integer',
        'type' => 'integer',
    ];

    protected function getItems() {
        return IndexMediaFile::orderBy('sorting_order', 'asc')->get();
    }

    protected function getItem($id) {
        return IndexMediaFile::find($id);
    }

    private function deleteImages($model) {
        File::delete($this->path_image, [$model->image_preview, $model->image_origin]);
    }

    private function workWithModel($model, $image_origin, $image_preview) {
        $model->title = request()->get('title');
        $model->url = request()->get('url');
        $model->description = request()->get('description');
        if ($image_origin !== null && $image_preview !== null) {
            $this->deleteImages($model);

            $model->image_preview = $image_preview;
            $model->image_origin = $image_origin;
        }
        $model->sorting_order = (request()->filled('sorting_order')) ? request()->get('sorting_order') : 0;
        $model->save();

        return $model;
    }

    protected function createModel($image_origin, $image_preview) {
        return $this->workWithModel(new Slider(), $image_origin, $image_preview);
    }

    protected function updateModel($image_origin, $image_preview) {
        return $this->workWithModel(Slider::find(request()->get('id')), $image_origin, $image_preview);
    }

    protected function destroyModel($id) {
        $model = IndexMediaFile::find($id);

        $this->deleteImages($model);

        $model->delete();
    }
}
