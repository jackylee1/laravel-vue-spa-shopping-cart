<?php

namespace App\Http\Controllers\Api;

use App\Models\Filter;
use App\Models\LinkToSocialNetwork;
use App\Models\Product;
use App\Models\Slider;
use App\Models\TextBlockTitle;
use App\Models\Type;
use App\Traits\DataTrait;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    use ValidateTrait,
        DataTrait;

    public function products(Request $request) {
        $this->setValidateRule([
            'type' => 'nullable|integer|exists:types,id',
            'category' => 'nullable|integer|exists:categories,id',
            'filters' => 'nullable|array',
            'filters.*' => 'integer|exists:filters,id|nullable',
            'sort' => 'nullable|in:all,from_cheap_to_expensive,from_expensive_to_cheap,popular,new,promotional'
        ]);
        $this->setValidateAttribute([
            'type' => 'Тип продукции',
            'category' => 'Категория',
            'filters' => 'Фильтра',
            'sort' => 'Сортировка'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $this->setData('products', Product::getProductsPublic());

        return response()->json($this->data);
    }
}
