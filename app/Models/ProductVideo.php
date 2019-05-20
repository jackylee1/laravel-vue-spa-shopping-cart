<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductVideo
 *
 * @property int $id
 * @property int $product_id
 * @property int $sorting_order
 * @property string $url
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductVideo disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductVideo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductVideo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductVideo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductVideo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductVideo whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductVideo whereSortingOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductVideo whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductVideo withCacheCooldownSeconds($seconds)
 * @mixin \Eloquent
 */
class ProductVideo extends Model
{
    use Cachable;

    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'url',
        'sorting_order'
    ];

    protected $casts = [
        'product_id' => 'integer',
        'sorting_order' => 'integer'
    ];

    private function workWithModel($model) {
        $model->product_id = request()->get('product_id');
        $model->sorting_order = request()->get('sorting_order');
        $model->url = request()->get('url');

        $model->save();

        return $model;
    }

    protected function createModel() {
        return $this->workWithModel(new ProductVideo());
    }

    protected function updateModel($id) {
        return $this->workWithModel(ProductVideo::find($id));
    }

    protected function destroyModel($id) {
        ProductVideo::find($id)->delete();
    }
}
