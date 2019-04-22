<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\NovaPoshtaArea;
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

        $cart_product = Cart::updateQuantityProduct();

        return response()->json([
            'status' => 'success',
            'cart_product' => $cart_product
        ]);
    }

    public function update(Request $request) {
        $this->setValidateRule([
            'cart_key' => 'required|string|exists:carts,key',
            'id' => 'required|integer|exists:carts,id',
            'user_name' => 'required|string|max:50',
            'user_surname' => 'nullable|string|max:50',
            'user_patronymic' => 'nullable|string|max:50',
            'phone' => 'required|phone:UA',
            'email' => 'nullable|email',
            'subscribe' => 'nullable|boolean',
            'delivery' => 'required|in:1',
            'area_id' => 'required|string',
            'city_id' => 'required|string',
            'warehouse_id' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('area_id') &&
                        $request->filled('city_id') &&
                        $request->filled('warehouse_id')) {
                        $check_warehouse = NovaPoshtaArea::getWarehouse([
                            'area_id' => $request->area_id,
                            'city_id' => $request->city_id,
                            'warehouse_id' => $request->warehouse_id
                        ]);
                        if ($check_warehouse === null) {
                            return $fail('Не коректные данные отделения Новой почты');
                        }
                    }
                }
            ],
            'order_payment_method_id' => 'required|integer|exists:order_payment_methods,id'
        ]);
        $this->setValidateAttribute([
            'cart_key' => 'Ключ к корзине',
            'user_name' => 'Имя',
            'user_surname' => 'Фамилия',
            'user_patronymic' => 'Отчество',
            'phone' => 'Телефон',
            'email' => 'E-Mail',
            'subscribe' => 'Подписаться на акции и новинки',
            'order_payment_method_id' => 'Способ оплаты',
            'delivery' => 'Способ доставки',
            'area_id' => 'Область',
            'city_id' => 'Населенный пункт',
            'warehouse_id' => 'Отделение'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $cart = Cart::updateModel();

        return response()->json([
            'status' => 'success',
            'cart' => $cart
        ]);
    }
}
