<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductImage
 *
 * @property int $id
 * @property int $product_id
 * @property int $sorting_order
 * @property string $origin
 * @property string $preview
 * @property int|null $main_status
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage whereMainStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage whereOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage wherePreview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductImage whereSortingOrder($value)
 * @mixin \Eloquent
 */
class ProductImage extends Model
{
    use Cachable;

    protected $fillable = ['product_id', 'sorting_order', 'origin', 'preview', 'main_status'];
    public $timestamps = false;

    public function product() {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    protected function updateModel() {
        $model = ProductImage::find(request()->get('id'));
        $model->sorting_order = request()->get('sorting_order');

        if (request()->get('main_status') == 1) {
            $model->product()->update([
                'main_image' => $model->id
            ]);
        }

        $model->main_status = request()->get('main_status');
        $model->save();

        return $model;
    }

    protected function updatePreviewImage($preview) {
        $model = ProductImage::find(request()->get('id'));
        $model->preview = $preview;
        $model->save();

        return $model;
    }
}
