<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Nestable\NestableTrait;

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
 */
class Category extends Model
{
    use Cachable,
        NestableTrait;

    public $timestamps = false;
    protected $fillable = [
        'type_id',
        'name',
        'like_name',
        'slug',
        'sorting_order',
        'parent_id',
        'show_on_header'
    ];
    protected $cats = [
        'type_id' => 'integer',
        'sorting_order' => 'integer',
        'parent_id' => 'integer',
        'show_on_header' => 'integer'
    ];

    protected $parent = 'parent_id';

    private $ids;

    protected $with = ['filters'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->ids = [];
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
        $model->parent_id = request()->get('parent_id');
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

    private function searchIds($data) {
        $count = count($data);
        $i = 0;
        while ($i < $count) {
            $this->ids[] = $data[$i]['id'];
            if (count($data[$i]['child']) > 0) {
                $this->searchIds($data[$i]['child']);
            }
            $i++;
        }
    }

    protected function destroyModel($id) {
        $this->ids[] = (int)$id;
        $this->searchIds(Category::parent((int)$id)->renderAsArray());

        Category::whereIn('id', $this->ids)->delete();

        return $this->ids;
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
}
