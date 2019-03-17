<?php

namespace App\Models;

use App\Tools\Models\ProductTool;
use Carbon\Carbon;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use App\Tools\File;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $article
 * @property string $slug
 * @property string $name
 * @property string $like_name
 * @property string $description
 * @property string $preview_description
 * @property string $like_preview_description
 * @property float $price
 * @property int|null $main_image
 * @property int $status
 * @property string|null $date_inclusion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProductBestseller $bestseller
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductInCategory[] $categories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductInFilter[] $filters
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductImage[] $images
 * @property-read \App\Models\ProductImage $mainImage
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereArticle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDateInclusion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereLikeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereLikePreviewDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereMainImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product wherePreviewDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereSharePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product withCacheCooldownSeconds($seconds)
 * @property float|null $discount_price
 * @property string|null $discount_start
 * @property string|null $discount_end
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductAvailable[] $available
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDiscountEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDiscountStart($value)
 */
class Product extends Model
{
    use Cachable;

    protected $fillable = [
        'article',
        'slug',
        'name',
        'like_name',
        'description',
        'preview_description',
        'like_preview_description',
        'price',
        'discount_price',
        'discount_start',
        'discount_end',
        'main_image',
        'status',
        'date_inclusion'
    ];

    protected $casts = [
        'price' => 'float',
        'discount_price' => 'float',
        'discount_start' => 'datetime',
        'discount_end' => 'datetime',
        'main_image' => 'integer',
        'status' => 'integer',
        'date_inclusion' => 'date'
    ];

    protected $with = [
        'images',
        'mainImage',
        'bestseller',
        'filters',
        'available'
    ];

    public $path_image = 'public/images/products/';

    public function images() {
        return $this->hasMany('App\Models\ProductImage');
    }

    public function available() {
        return $this->hasMany('App\Models\ProductAvailable', 'product_id', 'id');
    }

    public function mainImage() {
        return $this->hasOne('App\Models\ProductImage', 'id', 'main_image');
    }

    public function bestseller() {
        return $this->hasOne('App\Models\ProductBestseller');
    }

    public function filters() {
        return $this->hasMany('App\Models\ProductInFilter');
    }

    protected function getProducts() {
        $query = Product::query();
        if (request()->filled('q')) {
            $like_data = getLikeData(request()->get('q'));
            $query->whereRaw('lower(like_name) like ?', ["%{$like_data['str']}%"])
                ->orWhereRaw('lower(like_name) like ?', ["%{$like_data['add_spaces']}%"])
                ->orWhereRaw('lower(like_name) like ?', ["%{$like_data['clear_spaces']}%"])
                ->orWhereRaw('lower(like_name) like ?', ["%{$like_data['like']}%"]);
            $query->orWhereRaw('lower(like_preview_description) like ?', ["%{$like_data['str']}%"])
                ->orWhereRaw('lower(like_preview_description) like ?', ["%{$like_data['add_spaces']}%"])
                ->orWhereRaw('lower(like_preview_description) like ?', ["%{$like_data['clear_spaces']}%"])
                ->orWhereRaw('lower(like_preview_description) like ?', ["%{$like_data['like']}%"]);
        }
        if (request()->get('only_discounts') == 1) {
            $query->where('discount_price', '>', 0);
        }
        if (request()->filled('selected_type')) {
            $query->whereHas('filters', function ($query) {
                $query->where('type_id', request()->get('selected_type'));
                if (request()->filled('selected_categories')) {
                    $count_categories = count(request()->get('selected_categories'));
                    if ($count_categories > 0) {
                        $category_id = request()->get('selected_categories')[$count_categories-1];
                        $query->where('category_id', $category_id);
                    }
                }
                if (request()->filled('selected_filters') && count(request()->get('selected_filters')) > 0) {
                    $query->whereIn('filter_id', request()->get('selected_filters'));
                }
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(10);

        ProductTool::checkRelevanceDiscount($products->items());

        return $products;
    }

    public static function getProduct($id) {
        $product = Product::find($id);
        $product = ProductTool::checkRelevanceDiscount($product);

        return $product;
    }

    private function workWithModel($model) {
        $model->article = request()->get('article');
        $model->slug = str_slug(request()->get('name'), '-');
        $model->name = request()->get('name');
        $model->like_name = getOnlyCharacters(request()->get('name'));
        $model->description = request()->get('description');
        $model->preview_description = request()->get('preview_description');
        $model->like_preview_description = getOnlyCharacters(request()->get('preview_description'));
        $model->price = number_format((float)request()->get('price'), 2, '.', '');
        $model->discount_price = number_format((float)request()->get('discount_price'), 2, '.', '');
        if (request()->filled('discount_start') && request()->filled('discount_end')) {
            $model->discount_start = Carbon::parse(request()->get('discount_start'))->toDateTimeString();
            $model->discount_end = Carbon::parse(request()->get('discount_end'))->toDateTimeString();
        }
        $model->status = request()->get('status');
        $model->date_inclusion = request()->get('date_inclusion');

        $model->save();

        return $model;
    }

    protected function updateMainImage() {
        Product::find(request()->get('product_id'))->update([
            'main_image' => request()->get('image_id')
        ]);
    }

    protected function createModel() {
        return $this->workWithModel(new Product())->fresh();
    }

    protected function updateModel() {
        return $this->workWithModel(Product::find(request()->get('id')));
    }

    protected function destroyModel($id) {
        $model = Product::with(['images'])->find($id);
        $model->images->each(function ($image) {
            File::delete('/public/images/products/', [
                $image->preview, 
                $image->origin
            ]);
        });
        $model->delete();
    }

    protected function createImage($image_origin, $image_preview) {
        $product = Product::find(request()->get('product_id'));
        return $product->images()->create([
            'sorting_order' => (request()->get('sorting_order') !== null) 
                                ? request()->get('sorting_order') : 0,
            'origin' => request()->get('product_id').'/'.$image_origin,
            'preview' => request()->get('product_id').'/'.$image_preview,
        ]);
    }

    protected function deleteImage() {
        $product = Product::find(request()->get('product_id'));
        $image = $product->images()->where('id', request()->get('image_id'))->first();
        if ($product->main_image == $image->id) {
            $product->main_image = null;
            $product->save();
        }
        File::delete($this->path_image, [$image->preview, $image->origin]);

        $image->delete();
    }

    protected function addToFilter() {
        $product = Product::find(request()->get('product_id'));
        $filter = $product->filters()->create([
            'type_id' => request()->get('type_id'),
            'category_id' => request()->get('category_id'),
            'filter_id' => request()->get('filter_id')
        ]);
        $filter->setRelation('categories', collect([]));
        $filter->setRelation('filters', collect([]));
        if (is_array(request()->get('categories')) && count(request()->get('categories')) > 0) {
            $category_field = collect(['category_id']);
            $create_categories = collect(request()->get('categories'))->map(function($item) use ($category_field) {
                return $category_field->combine($item);
            });
            $create_models = $filter->categories()->createMany($create_categories->toArray());
            $filter->setRelation('categories', $create_models);
        }

        if (is_array(request()->get('filters')) && count(request()->get('filters')) > 0) {
            $filter_field = collect(['filter_id']);
            $create_filters = collect(request()->get('filters'))->map(function($item) use ($filter_field) {
                return $filter_field->combine($item);
            });
            $create_models = $filter->filters()->createMany($create_filters->toArray());
            $filter->setRelation('filters', $create_models);
        }

        return $filter;
    }
}
