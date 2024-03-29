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
 * @property string|null $image_index_origin
 * @property string|null $image_index_preview
 * @property int $show_on_header
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereImageIndexOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereImageIndexPreview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereShowOnCertificate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereShowOnHeader($value)
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
        'image_index_preview',
        'image_index_origin',
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
        'in_filter' => 'integer',
    ];

    public $timestamps = false;

    protected $with = ['categories', 'filters'];

    public $path_image = 'public/images/type/';

    private function deleteImages($model, $type = 1) {
        $images = [];
        switch ($type) {
            case 1:
                $images = [$model->image_preview, $model->image_origin];
                break;
            case 2:
                $images = [$model->image_index_preview, $model->image_index_origin];
                break;
            case 'all':
                $images = [
                    $model->image_preview,
                    $model->image_origin,
                    $model->image_index_preview,
                    $model->image_index_origin
                ];
                break;
        }
        File::delete($this->path_image, $images);
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

    public static function types($public = false, $filter_active = true) {
        $types = Type::with(['categories' => function ($query) use ($filter_active) {
            $query->with(['filters' => function ($query) use ($filter_active) {
                $query->join('filters', function ($join) use ($filter_active) {
                    $join->on('category_filters.filter_id', '=', 'filters.id');
                    if ($filter_active) {
                        $join->where('active', true);
                    }
                });
                $query->orderBy('filters.sorting_order', 'asc');
                $query->addSelect([
                    'category_filters.id',
                    'category_filters.filter_id',
                    'category_filters.category_id',
                    'filters.sorting_order',
                ]);
            }]);
            $query->orderBy('sorting_order', 'asc');
        }])->with(['filters' => function ($query) use ($filter_active) {
            $query->join('filters', function ($join) use ($filter_active) {
                $join->on('type_filters.filter_id', '=', 'filters.id');
                if ($filter_active) {
                    $join->where('active', true);
                }
            });
            $query->orderBy('filters.sorting_order', 'asc');
            $query->addSelect([
                'type_filters.id',
                'type_filters.filter_id',
                'type_filters.type_id',
                'filters.sorting_order',
            ]);
        }])->orderBy('sorting_order', 'asc')->get();

        if ($public) {
            $types = $types->map(function ($type) {
                $type->categories->map(function ($category) {
                    $id_filters = $category->filters->map->only(['filter_id'])->flatten();
                    $check_in_category_relation = ($category->type_id === 1) ? true : false;
                    $id_filters = $id_filters->filter(function ($id_filter)
                    use ($category, $check_in_category_relation) {
                        return ProductInFilterTree::findByFilterId($id_filter,
                                $category->type_id,
                                $category->id,
                                $check_in_category_relation) !== null;
                    });
                    $filters = $category->filters->whereIn('filter_id', $id_filters->toArray());

                    $category->setRelation('filters', collect(array_values($filters->toArray())));

                    return $category;
                });
                return $type;
            });
        }

        return $types;
    }

    private function workWithModel($model, $image_origin, $image_preview, $image_index_origin, $image_index_preview) {
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

        if ($image_index_origin !== null && $image_index_preview !== null) {
            $this->deleteImages($model, 2);

            $model->image_index_origin = $image_index_origin;
            $model->image_index_preview = $image_index_preview;
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

    protected function createModel($image_origin = null, $image_preview = null,
                                   $image_index_origin = null, $image_index_preview = null) {
        $model = $this->workWithModel(new Type(), $image_origin, $image_preview,
                                    $image_index_origin, $image_index_preview)->fresh();

        return $model;
    }

    protected function updateModel($image_origin = null, $image_preview = null,
                                   $image_index_origin = null, $image_index_preview = null) {
        $model = $this->workWithModel(Type::find(request()->get('id')), $image_origin, $image_preview,
                                    $image_index_origin, $image_index_preview);

        return $model;
    }

    protected function destroyModel($id) {
        $this->deleteImages(Type::find($id), 'all');
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

    public static function getTypeById($id) {
        return Type::setEagerLoads([])->find($id);
    }
}
