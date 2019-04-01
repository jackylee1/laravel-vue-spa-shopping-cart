<?php

namespace App\Models;

use App\Tools\File;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Nestable\NestableTrait;

/**
 * App\Models\Filter
 *
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property string $like_name
 * @property string $slug
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
    use NestableTrait,
        Cachable;

    protected $ids;
    protected $count_children, $count_parents;
    protected $fillable = [
        'parent_id',
        'name',
        'like_name',
        'slug',
        'type',
        'sorting_order',
        'show_on_index',
        'show_on_header',
        'show_on_footer',
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

    protected function getFilters() {
        return Filter::get();
    }

    protected function workWithModel($model, $image_origin, $image_preview) {
        $model->parent_id = request()->get('parent_id');
        $model->name = request()->get('name');
        $model->like_name = getOnlyCharacters(request()->get('name'));
        $model->slug = cleanStr(request()->get('slug'));
        $model->type = request()->get('type');
        $model->sorting_order = request()->get('sorting_order');
        $model->show_on_index = request()->get('show_on_index');
        $model->show_on_header = request()->get('show_on_header');
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
        return $this->workWithModel(new Filter(), $image_origin, $image_preview);
    }

    protected function updateModel($image_origin = null, $image_preview = null) {
        return $this->workWithModel(Filter::find(request()->get('id')), $image_origin, $image_preview);
    }

    protected function checkUniqueSlug() {
        $query = Filter::query();
        $parent = (request()->get('parent_id') !== null) ? request()->get('parent_id') : 0;
        if (request()->get('id') !== null) {
            $query->where('id', '<>', request()->get('id'));
        }
        $query->where([
            ['parent_id', $parent],
            ['slug', request()->get('slug')]
        ]);

        return $query->count();
    }

    private function searchIds($data) {
        $count = count($data);
        $i = 0;
        while ($i < $count) {
            $this->count_children += 1;
            $this->ids[] = $data[$i]['id'];
            if (count($data[$i]['child']) > 0) {
                $this->searchIds($data[$i]['child']);
            }
            $i++;
        }
    }

    private function searchParents($data) {
        $count = count($data);
        $i = 0;
        while ($i < $count) {
            if (!in_array($data[$i]['parent_id'], $this->ids)) {
                $this->ids[] = $data[$i]['parent_id'];
                $this->count_parents += 1;
            }
            if (count($data[$i]['child']) > 0) {
                $this->searchParents($data[$i]['child']);
            }
            $i++;
        }
    }

    protected function destroyModel($id) {
        $this->ids[] = (int)$id;
        $this->searchIds(Filter::parent((int)$id)->renderAsArray());

        $models = Filter::whereIn('id', $this->ids)->get();
        $models->each(function ($model) {
            $this->deleteImages($model);
        });

        Filter::whereIn('id', $this->ids)->delete();

        return $this->ids;
    }
}
