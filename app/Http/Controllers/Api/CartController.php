<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\ProductAvailable;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    use ValidateTrait;

    private function validationQuantity() {
        $this->setValidateRule([
            'quantity' => [
                'required',
                'min:1',
                'integer',
                function ($attribute, $value, $fail) {
                    if (\request()->filled('product_available_id')) {
                        $available = ProductAvailable::getItem(\request()->get('product_available_id'));
                        if (\request()->get('quantity') > $available->quantity) {
                            return $fail('Вы передали количество товара которое превышает количество товара в наличии');
                        }
                    }
                }
            ]
        ]);
        $this->setValidateAttribute(['quantity' => 'Количество товара']);
    }

    public function addProduct(Request $request) {
        $this->setValidateRule([
            'cart_key' => 'required|string|exists:carts,key',
            'product_id' => 'required|integer|exists:products,id',
            'product_available_id' => 'required|integer|exists:product_availables,id',
        ]);
        $this->validationQuantity();
        $this->setValidateAttribute([
            'cart_key' => 'Ключ к корзине',
            'product_id' => 'ID товара',
            'product_available_id' => 'ID наличия товара',
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $cart_product = Cart::addProduct();

        return response()->json([
            'cart_product' => $cart_product,
            'status' => 'success'
        ]);
    }

    public function deleteProduct(Request $request) {
        $this->setValidateRule([
            'cart_key' => 'required|string|exists:carts,key',
            'id' => 'required|integer|exists:cart_products,id'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        Cart::deleteProduct();

        return response()->json(['status' => 'success']);
    }

    public function updateQuantityProduct(Request $request) {
        $this->setValidateRule([
            'cart_key' => 'required|string|exists:carts,key',
            'id' => 'required|integer|exists:cart_products,id',
        ]);
        $this->validationQuantity();
        $this->setValidateAttribute([
            'cart_key' => 'Ключ к корзине'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        Cart::updateQuantityProduct();

        return response()->json(['status' => 'success']);
    }
}
