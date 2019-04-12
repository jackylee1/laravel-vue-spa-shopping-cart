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
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use ValidateTrait,
        DataTrait;

    public function products(Request $request) {
        if ($request->filled('filters')) {
            if (is_array($request->get('filters'))) {
                $this->setValidateRule([
                    'filters' => 'array',
                    'filters.*' => 'integer|exists:filters,id|nullable',
                ]);
            }
            else {
                $this->setValidateRule([
                    'filters' => 'string|exists:filters,id',
                ]);
            }
        }
        $this->setValidateRule([
            'type' => 'nullable|integer|exists:types,id',
            'category' => 'nullable|integer|exists:categories,id',
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

    public function view($slug) {
        $product = null;
        $this->setValidateRule([
            'slug' => [
                'required',
                'string',
                'exists:products,slug',
                function ($attribute, $value, $fail) use (&$product, $slug) {
                    if ($slug !== null) {
                        $product = Product::getProductPublic($slug);
                        if ($product === null) {
                            return $fail('По запросу нет продукции');
                        }
                    }
                }
            ]
        ]);
        $this->setValidateAttribute([
            'slug' => 'URL товара'
        ]);

        $validator = Validator::make(['slug' => $slug], $this->validate_rules, [], $this->validate_attributes);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'product' => $product
        ]);
    }
}
