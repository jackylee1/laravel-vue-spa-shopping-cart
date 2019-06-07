<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
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
                    'filters.*' => 'nullable|regex:/^(?!,$)[\d,(null),\[\]\"\"]+$/',
                ]);
            }
            else {
                $this->setValidateRule([
                    'filters' => 'string|exists:filters,id',
                ]);
            }
        }
        $this->setValidateRule([
            'limit' => 'nullable|integer|max:8',
            'text' => 'nullable|string|max:150',
            'autocomplete' => 'nullable|boolean',
            'type' => 'nullable|integer|exists:types,id',
            'category' => 'nullable|integer',
            'sort' => 'nullable|in:all,from_cheap_to_expensive,from_expensive_to_cheap,popular,new,promotional'
        ]);
        $this->setValidateAttribute([
            'type' => 'Тип продукции',
            'category' => 'Категория',
            'filters' => 'Фильтра',
            'sort' => 'Сортировка',
            'text' => 'Текст поиска'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        if ($request->filled('autocomplete')) {
            $request->merge([
                'limit' => 15,
            ]);
            $request->merge(request()->all());
            app()->instance('request', $request);

            $products = Product::getProductsPublic()->map(function ($product, $key) {
                return (object)[
                    'index' => $key,
                    'id' => $product->id,
                    'name' => $product->name,
                    'picture' => ''
                ];
            });

            $this->setData('products', $products);
        }
        else {
            $this->setData('products', Product::getProductsPublic());
        }

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
