<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductAvailable
 *
 * @property int $id
 * @property int $product_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailable query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductAvailable whereProductId($value)
 * @mixin \Eloquent
 */
class ProductAvailable extends Model
{
    use Cachable;

    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'quantity'
    ];
    protected $casts = [
        'product_id' => 'integer',
        'quantity' => 'integer'
    ];

    protected $with = [
        'filters'
    ];

    public function filters() {
        return $this->hasMany('App\Models\ProductAvailableFilter', 'product_available_id', 'id');
    }

    protected function getItem($id) {
        return ProductAvailable::find($id);
    }

    protected function createModel() {
        $product_available = ProductAvailable::create([
            'product_id' => request()->get('product_id'),
            'quantity' => request()->get('quantity')
        ]);

        $filter_field = collect(['filter_id']);
        $create_filters= collect(request()->get('filters'))->map(function($item) use ($filter_field) {
            return $filter_field->combine($item);
        });
        $filters = $product_available->filters()->createMany($create_filters->toArray());
        $product_available->setRelation('filters', $filters);

        return $product_available;
    }

    protected function updateQuantity() {
        ProductAvailable::find(request()->get('id'))->update([
            'quantity' => request()->get('quantity')
        ]);
    }

    protected function destroyModel() {
        ProductAvailable::find(request()->get('id'))->delete();
    }
}
