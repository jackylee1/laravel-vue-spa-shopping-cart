<?php

namespace App\Models;

use App\Tools\File;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LinkToSocialNetwork
 *
 * @property int $id
 * @property string $url
 * @property string $image_preview
 * @property string $image_origin
 * @property int $sorting_order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkToSocialNetwork newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkToSocialNetwork newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkToSocialNetwork query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkToSocialNetwork whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkToSocialNetwork whereImageOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkToSocialNetwork whereImagePreview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkToSocialNetwork whereSortingOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkToSocialNetwork whereUrl($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkToSocialNetwork disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LinkToSocialNetwork withCacheCooldownSeconds($seconds)
 */
class LinkToSocialNetwork extends Model
{
    use Cachable;

    public $timestamps = false;

    protected $fillable = [
        'url',
        'image_preview',
        'image_origin',
        'sorting_order'
    ];

    protected $casts = [
        'sorting_order' => 'integer'
    ];

    public $path_image = 'public/images/social_network/';

    public static function getLinks() {
        return LinkToSocialNetwork::orderBy('sorting_order', 'asc')->get();
    }

    protected function getLink($id) {
        return LinkToSocialNetwork::find($id);
    }

    private function deleteImages($model) {
        File::delete($this->path_image, [$model->image_preview, $model->image_origin]);
    }

    private function workWithModel($model, $image_origin, $image_preview) {
        $model->url = request()->get('url');
        if ($image_origin !== null && $image_preview !== null) {
            $this->deleteImages($model);

            $model->image_preview = $image_preview;
            $model->image_origin = $image_origin;
        }
        $model->sorting_order = (request()->filled('sorting_order')) ? request()->get('sorting_order') : 0;
        $model->save();

        return $model;
    }

    protected function createModel($image_origin, $image_preview) {
        return $this->workWithModel(new LinkToSocialNetwork(), $image_origin, $image_preview);
    }

    protected function updateModel($image_origin, $image_preview) {
        return $this->workWithModel(LinkToSocialNetwork::find(request()->get('id')), $image_origin, $image_preview);
    }

    protected function destroyModel($id) {
        $model = LinkToSocialNetwork::find($id);

        $this->deleteImages($model);

        $model->delete();
    }
}
