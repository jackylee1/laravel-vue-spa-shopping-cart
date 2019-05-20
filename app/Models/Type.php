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
 * @property int|null $show_on_index
 * @property int|null $show_on_footer
 * @property int|null $show_on_certificate
 * @property string|null $image_preview
 * @property string|null $image_origin
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereImageOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereImagePreview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereShowOnFooter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereShowOnIndex($value)
 * @property string|null $m_title
 * @property string|null $m_description
 * @property string|null $m_keywords
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereMDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereMKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereMTitle($value)
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
        'show_on_footer',
        'show_on_certificate',
        'show_on_header',
        'm_title',
        'm_description',
        'm_keywords'
    ];
    protected $casts = [
        'show_on_index' => 'integer',
        'show_on_footer' => 'integer',
        'show_on_certificate' => 'integer',
        'show_on_header' => 'integer',
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

    public static function getTypeBySlug($slug) {
        return Type::where('slug', $slug)->first();
    }

    public static function types() {
        return Type::with(['categories' => function ($query) {
            $query->orderBy('sorting_order', 'asc');
        }])->with(['filters' => function ($query) {
            $query->join('filters', function ($join) {
                $join->on('type_filters.filter_id', '=', 'filters.id');
            });
            $query->orderBy('filters.sorting_order', 'asc');
            $query->addSelect([
                'type_filters.id',
                'type_filters.filter_id',
                'type_filters.type_id',
                'filters.sorting_order',
            ]);
        }])->orderBy('sorting_order', 'asc')->get();
    }

    private function workWithModel($model, $image_origin, $image_preview) {
        $type_certificate = Type::where('show_on_certificate', true)->first();

        $model->name = request()->get('name');
        $model->slug = cleanStr(request()->get('slug'));
        $model->sorting_order = request()->get('sorting_order');
        $model->show_on_index = request()->get('show_on_index');
        $model->show_on_footer = request()->get('show_on_footer');
        $model->show_on_certificate = request()->get('show_on_certificate');
        $model->show_on_header = request()->get('show_on_header');

        if ($image_origin !== null && $image_preview !== null) {
            $this->deleteImages($model);

            $model->image_origin = $image_origin;
            $model->image_preview = $image_preview;
        }

        $model->m_title = request()->get('m_title');
        $model->m_description = request()->get('m_description');
        $model->m_keywords = request()->get('m_keywords');

        $model->save();

        if ($type_certificate !== null && $model->show_on_certificate && $type_certificate->id !== $model->id) {
            $type_certificate->show_on_certificate = false;
            $type_certificate->save();
        }

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
