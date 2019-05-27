<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Filter;
use App\Models\Product;
use App\Models\Type;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function exportToXml($key) {
        if ($key !== env('ROUTE_KEY')) {
            return redirect()->to('/');
        }

        $types = Type::types()->map(function ($type) {
            $categories = $type->categories->map(function ($category) {
                if ($category->parent_id === 1) {
                    $category->parent_id = 0;
                }

                return $category;
            });
            $categories = collect(\Nestable::make($categories)->renderAsArray());

            $type->setRelation('categories', $categories);

            return $type;
        });
        $filters = Filter::getFilters();
        $products = Product::getProductsPublic(true);

        return response()->view('export_to_xml', [
            'products' => $products,
            'types' => $types,
            'filters' => $filters,
            'categories' => Category::getCategories(),
            'name' => env('APP_NAME'),
            'company' => env('APP_NAME'),
            'url' => env('APP_URL'),
        ])->header('Content-Type', 'text/xml;charset=utf-8');
    }
}
