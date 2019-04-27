<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TextBlockData
 *
 * @property int $id
 * @property int $text_block_title_id
 * @property string $title
 * @property int $type
 * @property string|null $url
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData whereTextBlockTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData whereUrl($value)
 * @mixin \Eloquent
 * @property int $sorting_order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData whereSortingOrder($value)
 * @property string|null $slug
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TextBlockData withCacheCooldownSeconds($seconds)
 */
class TextBlockData extends Model
{
    use Cachable;

    protected $fillable = [
        'text_block_title_id',
        'title',
        'type',
        'url',
        'description',
        'sorting_order',
        'slug',
        'm_title',
        'm_description',
        'm_keywords'
    ];
    protected $casts = [
        'text_block_title_id' => 'integer',
        'type' => 'integer',
        'sorting_order' => 'integer',
    ];
    public $timestamps = false;

    protected function getItems() {
        return TextBlockData::orderBy('sorting_order', 'asc')->get();
    }

    protected function getData($id) {
        return TextBlockData::find($id);
    }

    private function workWithModel($model) {
        $model->title = request()->get('title');
        $model->text_block_title_id = request()->get('text_block_title_id');
        $model->type = request()->get('type');
        $model->url = request()->get('url');
        if (request()->filled('slug')) {
            $model->slug = request()->get('slug');
        }
        $model->description = request()->get('description');
        $model->sorting_order = request()->filled('sorting_order') ? request()->get('sorting_order') : 0;

        $model->m_title = request()->get('m_title');
        $model->m_description = request()->get('m_description');
        $model->m_keywords = request()->get('m_keywords');

        $model->save();

        return $model;
    }

    protected function createModel() {
        return $this->workWithModel(new TextBlockData());
    }

    protected function updateModel($id) {
        return $this->workWithModel(TextBlockData::find($id));
    }

    protected function destroyModel($id) {
        TextBlockData::find($id)->delete();
    }
}
