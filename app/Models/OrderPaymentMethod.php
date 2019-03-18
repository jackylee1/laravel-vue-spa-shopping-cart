<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderPaymentMethod
 *
 * @property int $id
 * @property string $name
 * @property int $sorting_order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPaymentMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderPaymentMethod whereSortingOrder($value)
 * @mixin \Eloquent
 */
class OrderPaymentMethod extends Model
{
    use Cachable;

    public $timestamps = false;
    protected $fillable = [
        'name',
        'sorting_order'
    ];
    protected $casts = [
        'sorting_order' => 'integer'
    ];

    private function workWithModel($model) {
        $model->name = request()->get('name');
        $model->sorting_order = (request()->filled('sorting_order')) ? request()->get('sorting_order') : 0;
        $model->save();

        return $model;
    }

    protected function createModel() {
        return $this->workWithModel(new OrderPaymentMethod());
    }

    protected function updateModel($id) {
        return $this->workWithModel(OrderPaymentMethod::find($id));
    }

    protected function methods() {
        return OrderPaymentMethod::orderBy('sorting_order', 'asc')->get();
    }

    protected function method($id) {
        return OrderPaymentMethod::find($id);
    }

    protected function destroyModel($id) {
        OrderPaymentMethod::find($id)->delete();
    }
}
