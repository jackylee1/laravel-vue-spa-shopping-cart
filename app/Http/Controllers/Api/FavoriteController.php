<?php

namespace App\Http\Controllers\Api;

use App\Models\Favorite;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavoriteController extends Controller
{
    use ValidateTrait;

    public function store(Request $request) {
        $this->setValidateRule([
            'product_id' => 'required|integer|exists:products,id',
            'favorite_key' => 'required|string|exists:favorites,key'
        ]);
        $this->setValidateAttribute([
            'product_id' => 'ID продукта',
            'favorite_key' => 'Ключ к избранным'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $favorite_product = Favorite::addProduct();

        return response()->json([
            'status' => 'success',
            'favorite_product' => $favorite_product
        ]);
    }

    public function destroy(Request $request) {
        $this->setValidateRule([
            'id' => 'required|integer|exists:favorite_products,id',
            'favorite_key' => 'required|string|exists:favorites,key'
        ]);
        $this->setValidateAttribute([
            'favorite_key' => 'Ключ к избранным'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        Favorite::firstOrCreateModel()->products()->where('id', $request->id)->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
