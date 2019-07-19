<?php

namespace App\Models;

use App\Tools\File;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Slider
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $url
 * @property string $image_origin
 * @property string $image_preview
 * @property int $sorting_order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereImageOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereImagePreview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereSortingOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereUrl($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider withCacheCooldownSeconds($seconds)
 * @property string|null $title_align
 * @property string|null $title_color
 * @property string|null $btn_align
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereBtnAlign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereTitleAlign($value)
 */
class Slider extends Model
{
    use Cachable;

    public $timestamps = false;
    protected $fillable = [
        'title',
        'description',
        'url',
        'image_preview',
        'image_origin',
        'sorting_order',
        'title_align',
        'btn_align',
        'title_color'
    ];
    public $path_image = 'public/images/slider/';

    protected function getSliders() {
        return Slider::orderBy('sorting_order', 'asc')->paginate(10);
    }

    public static function getAllSliders() {
        return Slider::orderBy('sorting_order', 'asc')->get();
    }

    protected function getSlider($id) {
        return Slider::find($id);
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
        $model->title_align = request()->get('title_align');
        $model->title_color = request()->get('title_color');
        $model->btn_align = request()->get('btn_align');
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
        $model = Slider::find($id);

        $this->deleteImages($model);

        $model->delete();
    }
}
