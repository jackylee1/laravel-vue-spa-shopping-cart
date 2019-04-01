<?php

namespace App\Models;

use App\Tools\File;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Type
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $sorting_order
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TypeFilter[] $filters
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereSortingOrder($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type withCacheCooldownSeconds($seconds)
 */
class Type extends Model
{
    use Cachable;

    protected $fillable = [
        'name',
        'sorting_order',
        'slug',
        'image_preview',
        'image_origin',
        'show_on_index',
        'show_on_footer'
    ];
    protected $casts = [
        'show_on_index' => 'integer',
        'show_on_footer' => 'integer'
    ];

    public $timestamps = false;

    protected $with = ['categories', 'filters'];

    public $path_image = 'public/images/type/';

    private function deleteImages($model) {
        File::delete($this->path_image, [$model->image_preview, $model->image_origin]);
    }

    public function filters() {
        return $this->hasMany('App\Models\TypeFilter');
    }

    public function categories() {
        return $this->hasMany('App\Models\Category')->orderBy('sorting_order', 'asc');
    }

    protected function types() {
        return Type::orderBy('sorting_order', 'asc')->get();
    }

    private function workWithModel($model, $image_origin, $image_preview) {
        $model->name = request()->get('name');
        $model->slug = cleanStr(request()->get('slug'));
        $model->sorting_order = request()->get('sorting_order');
        $model->show_on_index = request()->get('show_on_index');
        $model->show_on_footer = request()->get('show_on_footer');

        if ($image_origin !== null && $image_preview !== null) {
            $this->deleteImages($model);

            $model->image_origin = $image_origin;
            $model->image_preview = $image_preview;
        }

        $model->save();

        return $model;
    }

    protected function createModel($image_origin = null, $image_preview = null) {
        $model = $this->workWithModel(new Type(), $image_origin, $image_preview)->fresh();

        return $model;
    }

    protected function updateModel($image_origin = null, $image_preview = null) {
        $model = $this->workWithModel(Type::find(request()->get('id')), $image_origin, $image_preview);

        return $model;
    }

    protected function destroyModel($id) {
        $this->deleteImages(Type::find($id));
        Type::find($id)->delete();
    }

    protected function handleFilter() {
        $operation = 'add';
        $model = null;

        $type = Type::find(request()->get('type_id'));

        if ($type->filters->where('filter_id', request()->get('filter_id'))->count() > 0) {
            $operation = 'remove';
            $type->filters()->where('filter_id', request()->get('filter_id'))->delete();
        }
        else {
            $model = $type->filters()->create([
                'filter_id' => request()->get('filter_id')
            ]);
        }

        return [
            'operation' => $operation,
            'model' => $model
        ];
    }
}
