<?php

namespace App\Http\Controllers\Api;

use App\Events\AdminEvent;
use App\Models\Cart;
use App\Models\NovaPoshtaArea;
use App\Models\ProductAvailable;
use App\Models\PromotionalCode;
use App\Models\Subscribe;
use App\Notifications\Admin\SendSubscribeNotification;
use App\Traits\ValidateTrait;
use App\User;
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
            'phone' => 'required|phone:UA|max:191',
            'email' => 'nullable|email|max:191',
            'subscribe' => 'nullable|boolean',
            'delivery' => 'required|in:1',
            'area_id' => 'required|string|max:191',
            'city_id' => 'required|string|max:191',
            'note' => 'nullable|string|max:2000',
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
            'warehouse_id' => 'Отделение',
            'note' => 'Комментарий к заказу'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        if ($request->filled('subscribe') && $request->subscribe) {
            $subscribe = Subscribe::getSubscribeByEmail($request->email);
            if ($subscribe === null) {
                $subscribe = Subscribe::firstOrCreateModel($request->email);

                User::getUser(1)->notify(new SendSubscribeNotification($subscribe->id));
                event(new AdminEvent('subscribe', $subscribe));
            }
        }

        $cart = Cart::updateModel();

        return response()->json([
            'status' => 'success',
            'cart' => $cart
        ]);
    }

    public function updatePromotionalCode(Request $request) {
        $promotional_code = null;

        $this->setValidateRule([
            'cart_key' => 'required|string|exists:carts,key',
            'code' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) use (&$promotional_code) {
                    if (\request()->filled('code')) {
                        $promotional_code = PromotionalCode::getModelByCode(\request()->get('code'));
                        if ($promotional_code !== null) {
                            if (!$promotional_code->status) {
                                return $fail('Указанный промо-код был использован или перешел в статус не активного кода');
                            }
                        }
                        else {
                            return $fail('Промо-код введен не верно.');
                        }
                    }
                }
            ]
        ]);
        $this->setValidateAttribute([
            'cart_key' => 'Ключ к корзине',
            'code' => 'Промо-код',
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $promotional_code_id = null;
        if ($promotional_code !== null) {
            $promotional_code_id = $promotional_code->id;
        }

        $cart = Cart::updatePromotionalCode($promotional_code_id);

        return response()->json([
            'status' => 'success',
            'cart' => $cart
        ]);
    }
}
