<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SizeTable
 *
 * @property int $id
 * @property int $sorting_order
 * @property string $title
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SizeTable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SizeTable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SizeTable query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SizeTable whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SizeTable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SizeTable whereSortingOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SizeTable whereTitle($value)
 * @mixin \Eloquent
 */
class SizeTable extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'sorting_order',
        'title',
        'description'
    ];

    protected $casts = [
        'sorting_order' => 'integer'
    ];

    public static function getSizes() {
        return SizeTable::orderBy('sorting_order', 'asc')->get();
    }

    protected function getSize($id) {
        return SizeTable::find($id);
    }

    private function workWithModel($model) {
        $model->title = request()->get('title');
        $model->description = request()->get('description');
        $model->sorting_order = request()->filled('sorting_order') ? request()->get('sorting_order') : 0;
        $model->save();

        return $model;
    }

    protected function createModel() {
        return $this->workWithModel(new SizeTable());
    }

    protected function updateModel($id) {
        return $this->workWithModel(SizeTable::find($id));
    }

    protected function destroyModel($id) {
        SizeTable::find($id)->delete();
    }
}
