<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TextBlockTitle
 *
 * @property int $id
 * @property int $sorting_order
 * @property string $title
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockTitle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockTitle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockTitle query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockTitle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockTitle whereSortingOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockTitle whereTitle($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockTitle disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockTitle withCacheCooldownSeconds($seconds)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TextBlockData[] $dataPage
 */
class TextBlockTitle extends Model
{
    use Cachable;

    protected $fillable = [
        'sorting_order',
        'title'
    ];
    protected $casts = [
        'sorting_order' => 'integer'
    ];
    public $timestamps = false;

    public function dataPage() {
        return $this->hasMany('App\Models\TextBlockData', 'text_block_title_id', 'id');
    }

    public static function getTitlesAndData() {
        return TextBlockTitle::with(['dataPage' => function ($query) {
            $query->orderBy('sorting_order', 'asc');
        }])->orderBy('sorting_order', 'asc')->get();
    }

    protected function getTitles() {
        return TextBlockTitle::orderBy('sorting_order', 'asc')->get();
    }

    protected function getTitle($id) {
        return TextBlockTitle::find($id);
    }

    private function workWithModel($model) {
        $model->title = request()->get('title');
        $model->sorting_order = request()->filled('sorting_order') ? request()->get('sorting_order') : 0;
        $model->save();

        return $model;
    }

    protected function createModel() {
        return $this->workWithModel(new TextBlockTitle());
    }

    protected function updateModel($id) {
        return $this->workWithModel(TextBlockTitle::find($id));
    }

    protected function destroyModel($id) {
        TextBlockTitle::find($id)->delete();
    }
}
