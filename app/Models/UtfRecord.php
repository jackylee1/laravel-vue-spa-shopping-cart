<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UtfRecord
 *
 * @property int $id
 * @property int $sorting_order
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UtfRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UtfRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UtfRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UtfRecord whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UtfRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UtfRecord whereSortingOrder($value)
 * @mixin \Eloquent
 */
class UtfRecord extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'sorting_order',
        'description'
    ];

    protected $casts = [
        'sorting_order' => 'integer'
    ];

    public static function getItems() {
        return UtfRecord::orderBy('sorting_order', 'asc')->get();
    }

    protected function getItem($id) {
        return UtfRecord::find($id);
    }

    private function workWithModel($model) {
        $model->description = request()->get('description');
        $model->sorting_order = request()->filled('sorting_order') ? request()->get('sorting_order') : 0;
        $model->save();

        return $model;
    }

    protected function createModel() {
        return $this->workWithModel(new UtfRecord());
    }

    protected function updateModel($id) {
        return $this->workWithModel(UtfRecord::find($id));
    }

    protected function destroyModel($id) {
        UtfRecord::find($id)->delete();
    }
}
