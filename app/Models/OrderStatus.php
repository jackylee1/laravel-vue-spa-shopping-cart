<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderStatus
 *
 * @property int $id
 * @property string $name
 * @property string $color
 * @property int $sorting_order
 * @property int $default
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderStatus whereDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderStatus whereSortingOrder($value)
 * @mixin \Eloquent
 */
class OrderStatus extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'color',
        'sorting_order',
        'default'
    ];
    protected $casts = [
        'sorting_order' => 'integer',
        'default' => 'integer'
    ];

    private static function workWithModel($model) {
        $model->name = request()->get('name');
        $model->color = request()->get('color');
        $model->default = (request()->filled('default')) ? request()->get('default') : false;
        $model->sorting_order = request()->get('sorting_order');
        $model->save();

        return $model;
    }
    public static function statuses() {
        return OrderStatus::orderBy('sorting_order', 'asc')->get();
    }

    protected function getStatus($id) {
        return $this::find($id);
    }

    public static function getDefault() {
        return OrderStatus::where('default', true)->first();
    }

    protected function updateModel() {
        return self::workWithModel($this::find(request()->get('id')));
    }

    protected function createModel() {
        return self::workWithModel(new $this);
    }

    public static function createDefaultStatus() {
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'name' => 'Ожидает обработки',
            'default' => 1,
            'color' => '#000',
            'sorting_order' => 0
        ]);
        $request->merge(request()->all());
        app()->instance('request', $request);

        $status = self::workWithModel(new OrderStatus());

        return $status;
    }

    protected function resetDefault() {
        $select = collect(OrderStatus::select(['id'])->get());
        $ids = [];
        $select->each(function ($item) use (&$ids) {
            $ids[] = $item->id;
        });
        $this::whereIn('id', $ids)->update(['default' => false]);
    }

    protected function destroyModel($id) {
        $this::find($id)->delete();
    }
}