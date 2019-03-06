<?php

namespace App\Models;

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
 */
class Type extends Model
{
    use Cachable;

    protected $fillable = ['name', 'sorting_order', 'slug'];
    public $timestamps = false;

    protected $with = ['categories', 'filters'];

    public function filters() {
        return $this->hasMany('App\Models\TypeFilter');
    }

    public function categories() {
        return $this->hasMany('App\Models\Category')->orderBy('sorting_order', 'asc');
    }

    protected function types() {
        return Type::orderBy('sorting_order', 'asc')->get();
    }

    private function workWithModel($model) {
        $model->name = request()->get('name');
        $model->slug = cleanStr(request()->get('slug'));
        $model->sorting_order = request()->get('sorting_order');

        $model->save();

        return $model;
    }

    protected function createModel() {
        $model = $this->workWithModel(new Type())->fresh();

        return $model;
    }

    protected function updateModel() {
        $model = $this->workWithModel(Type::find(request()->get('id')));

        return $model;
    }

    protected function destroyModel($id) {
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
