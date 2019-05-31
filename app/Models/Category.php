<?php

namespace App\Models;

use App\Jobs\ReassignCategoryProducts;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Category
 *
 * @property int $id
 * @property int|null $type_id
 * @property string $name
 * @property string $like_name
 * @property string $slug
 * @property int $sorting_order
 * @property int $parent_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CategoryFilter[] $filters
 * @property-read \App\Models\Type $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereLikeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereSortingOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereTypeId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category withCacheCooldownSeconds($seconds)
 * @property int|null $show_on_header
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereShowOnHeader($value)
 * @property string|null $m_title
 * @property string|null $m_description
 * @property string|null $m_keywords
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereMDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereMKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereMTitle($value)
 * @property int|null $hidden_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereHiddenName($value)
 */
class Category extends Model
{
    use Cachable;

    public $timestamps = false;
    protected $fillable = [
        'type_id',
        'name',
        'like_name',
        'slug',
        'sorting_order',
        'parent_id',
        'show_on_header',
        'm_title',
        'm_description',
        'm_keywords',
        'hidden_name',
        'active_link'
    ];
    protected $cats = [
        'type_id' => 'integer',
        'sorting_order' => 'integer',
        'parent_id' => 'integer',
        'show_on_header' => 'integer',
        'hidden_name' => 'integer',
        'active_link' => 'integer'
    ];

    protected $parent = 'parent_id';

    private static $model_ids;

    protected $with = ['filters'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        self::$model_ids = [];
    }

    public function type() {
        return $this->hasOne('App\Models\Type', 'id', 'type_id');
    }

    public function filters() {
        return $this->hasMany('App\Models\CategoryFilter', 'category_id', 'id');
    }

    public static function getCategoryBySlug($slug) {
        return Category::where('slug', $slug)->first();
    }

    private function workWithModel($model) {
        if ($model->id !== null && $model->parent_id !== request()->get('parent_id')) {
            $new_categories = (request()->get('parent_id') == 1) ? [$model->id] : [request()->get('parent_id'), $model->id];
            $old_categories = ($model->parent_id == 1) ? [$model->id] : [$model->parent_id, $model->id];

            if ($new_categories !== $old_categories) {
                dispatch_now(new ReassignCategoryProducts($model->type_id, $old_categories, $new_categories));
            }
        }

        $model->parent_id = (request()->get('parent_id') == 0) ? 1 : request()->get('parent_id');
        $model->type_id = request()->get('type_id');
        $model->name = request()->get('name');
        $model->like_name = getOnlyCharacters(request()->get('name'));
        $model->slug = cleanStr(request()->get('slug'));
        $model->sorting_order = (request()->get('sorting_order') !== null)
                                ? request()->get('sorting_order')
                                : null;
        $model->show_on_header = (request()->filled('show_on_header'))
            ? request()->get('show_on_header')
            : 1;
        $model->hidden_name = (request()->filled('hidden_name'))
            ? request()->get('hidden_name')
            : 0;

        if ($model->parent_id == 1) {
            $model->active_link = (request()->filled('active_link'))
                ? request()->get('active_link')
                : 1;
        }
        else {
            $model->active_link = 1;
        }

        $model->m_title = request()->get('m_title');
        $model->m_description = request()->get('m_description');
        $model->m_keywords = request()->get('m_keywords');

        $model->save();

        return $model;
    }

    protected function checkUniqueSlug() {
        $query = Category::query();
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

    protected function createModel() {
        return $this->workWithModel(new Category())->fresh();
    }

    protected function updateModel() {
        return $this->workWithModel(Category::with('type')->find(request()->get('id')));
    }

    public static function getChildrenCategories($id) {
        $model_ids = [];
        Category::where('parent_id', $id)->get()->each(function ($category) use (&$model_ids) {
            $model_ids[] = $category->id;
        });

        return $model_ids;
    }

    public static function destroyModel($id) {
        $model_ids[] = (int)$id;
        $categories = Category::where('parent_id', $id)->get();
        $categories->each(function ($category) use (&$model_ids) {
            $model_ids[] = $category->id;
        });

        Category::whereIn('id', $model_ids)->delete();

        return $model_ids;
    }

    protected function handleFilter() {
        $operation = 'add';
        $model = null;

        $category = Category::find(request()->get('category_id'));

        if ($category->filters->where('filter_id', request()->get('filter_id'))->count() > 0) {
            $operation = 'remove';
            $category->filters()->where('filter_id', request()->get('filter_id'))->delete();
        }
        else {
            $model = $category->filters()->create([
                'filter_id' => request()->get('filter_id')
            ]);
        }

        return [
            'operation' => $operation,
            'model' => $model
        ];
    }

    public static function getCategories() {
        return Category::select('*')->get();
    }

    public static function getItemsByIds($id_categories) {
        return \DB::table('categories')->whereIn('id', $id_categories)->orderBy('parent_id', 'asc')->get();
    }
}
