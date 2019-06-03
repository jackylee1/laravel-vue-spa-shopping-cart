<?php

namespace App\Models;

use App\Tools\File;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Filter
 *
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property string $like_name
 * @property int $type
 * @property int $sorting_order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereLikeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereSortingOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereType($value)
 * @mixin \Eloquent
 * @property int $show_on_index
 * @property int|null $show_on_header
 * @property int|null $show_on_footer
 * @property string|null $image_origin
 * @property string|null $image_preview
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereImageOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereImagePreview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereShowOnFooter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereShowOnHeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereShowOnIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter whereTypeIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Filter withCacheCooldownSeconds($seconds)
 */
class Filter extends Model
{
    use Cachable;

    protected $ids;
    protected $count_children, $count_parents;
    protected $fillable = [
        'parent_id',
        'name',
        'like_name',
        'type',
        'sorting_order',
        'show_on_index',
        'show_on_header',
        'show_on_footer',
        'show_image',
        'image_origin',
        'image_preview'
    ];
    protected $casts = [
        'id' => 'integer',
        'parent_id' => 'integer',
        'type' => 'integer',
        'sorting_order' => 'integer',
        'show_on_index' => 'integer',
        'show_on_header' => 'integer',
        'show_on_footer' => 'integer',
        'show_image' => 'integer',
    ];
    public $timestamps = false;
    protected $parent = 'parent_id';
    public $path_image = 'public/images/filter/';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->ids = [];
        $this->count_children = 0;
    }

    private function deleteImages($model) {
        File::delete($this->path_image, [$model->image_preview, $model->image_origin]);
    }

    public static function getFilters() {
        return Filter::orderBy('sorting_order', 'asc')->get();
    }

    public static function getFiltersById($id) {
        if (is_array($id)) {
            return Filter::whereIn('id', $id)->get();
        }
        else {
            return Filter::find($id);
        }
    }

    protected function workWithModel($model, $image_origin, $image_preview) {
        $model->parent_id = request()->get('parent_id');
        $model->name = request()->get('name');
        $model->like_name = getOnlyCharacters(request()->get('name'));
        $model->type = request()->get('type');
        $model->sorting_order = request()->get('sorting_order');
        $model->show_on_index = request()->get('show_on_index');
        $model->show_on_header = request()->get('show_on_header');
        $model->show_on_footer = request()->get('show_on_footer');
        $model->show_image = request()->get('show_image');

        if ($image_origin !== null && $image_preview !== null) {
            $this->deleteImages($model);

            $model->image_origin = $image_origin;
            $model->image_preview = $image_preview;
        }

        $model->save();

        return $model;
    }

    protected function createModel($image_origin = null, $image_preview = null) {
        return $this->workWithModel(new Filter(), $image_origin, $image_preview);
    }

    protected function updateModel($image_origin = null, $image_preview = null) {
        return $this->workWithModel(Filter::find(request()->get('id')), $image_origin, $image_preview);
    }

    protected function destroyModel($id) {
        $ids[] = (int)$id;
        $models = Filter::where('parent_id', $id)->get();

        $models->each(function ($filter) use (&$ids) {
            $ids[] = $filter->id;
        });

        $models->each(function ($model) {
            $this->deleteImages($model);
        });

        Filter::whereIn('id', $ids)->delete();

        return $ids;
    }
}
