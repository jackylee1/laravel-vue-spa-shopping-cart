<?php

namespace App\Http\Controllers\Api;

use App\Events\AdminEvent;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\PromotionalCode;
use App\Notifications\PublicNotification\SendCreateOrderNotification;
use App\Traits\ValidateTrait;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    use ValidateTrait;

    private function sendToAdminCreateNotification($order_id) {
        User::getUser(1)->notify(new \App\Notifications\Admin\SendCreateOrderNotification($order_id));
    }

    public function create(Request $request) {
        $cart = Cart::firstOrCreateModel();

        $this->setValidateRule([
            'cart_key' => [
                'required',
                'string',
                'exists:carts,key',
                function ($attribute, $value, $fail) use ($cart) {
                    if ($cart->promotionalCode !== null && !$cart->promotionalCode->status) {
                        return $fail('Промо-код который прикреплен к заказу не активный');
                    }
                    if ($cart->products->count() === 0) {
                        return $fail('В вашей корзине нет товаров');
                    }
                }
            ]
        ]);
        $this->setValidateAttribute([
            'cart_key' => 'Ключ к корзине'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $order = Order::createModel($cart->note);
        if (auth()->check()) {
            $order->user_id = auth()->user()->id;
        }
        $order->user_name = $cart->user_name;
        $order->user_surname = $cart->user_surname;
        $order->phone = $cart->phone;
        $order->email = $cart->email;
        $order->delivery_method = $cart->delivery;
        $order->area_id = $cart->area_id;
        $order->city_id = $cart->city_id;
        $order->warehouse_id = $cart->warehouse_id;
        $order->order_payment_method_id = $cart->order_payment_method_id;
        $order->promotional_code_id = $cart->user_promotional_code_id;

        $promotional_code = null;

        if ($order->promotional_code_id !== null) {
            $promotional_code = PromotionalCode::getCodeById($order->promotional_code_id);
            $promotional_code->status = 0;
            $promotional_code->save();
        }

        $products = [];
        $cart->products->each(function ($cart_product) use (&$products) {
            $product = Product::getProduct($cart_product->product_id);
            $available = $product->available()
                ->where('id', $cart_product->product_available_id)
                ->first();

            if ($available !== null && $available->quantity > $cart_product->quantity) {
                $price = $product->price * $cart_product->quantity;
                $discount_price = $product->discount_price * $cart_product->quantity;

                $products[] = [
                    'product_id' => $product->id,
                    'product_available_id' => $cart_product->product_available_id,
                    'price' => $price,
                    'discount_price' => $discount_price,
                    'product_price' => $product->price,
                    'product_discount_price' => $product->discount_price,
                    'quantity' => $cart_product->quantity
                ];

                $available->update([
                    'quantity' => $available->quantity - request()->get('quantity')
                ]);

                $bestseller = $product->bestseller()->firstOrCreate([]);
                $bestseller->update([
                    'quantity' => $bestseller->quantity + $cart_product->quantity
                ]);
            }
        });

        $products = $order->products()->createMany($products);
        $order->setRelation('products', $products);

        $order->save();

        $order = Order::recalculatePrice($order->fresh(), $promotional_code);

        $order->save();

        $cart->user_promotional_code_id = null;
        $cart->products()->delete();
        $cart->save();

        $order = Order::getOrder($order->id);
        $order->load([
            'status'
        ]);

        if ($order->email !== null) {
            Notification::route('mail', $order->email)
                ->notify(new SendCreateOrderNotification($order));

        }

        $this->sendToAdminCreateNotification($order);

        event(new AdminEvent('order', $order));

        return response()->json([
            'status' => 'success',
            'cart' => Cart::firstOrCreateModel(),
            'order' => $order
        ]);
    }

    public function getOrders() {
        return response()->json([
            'orders' => Order::getOrdersPublic()
        ]);
    }

    public function view(Request $request) {
        $order = null;
        $this->setValidateRule([
            'order_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($request, &$order) {
                    if ($request->filled('order_id')) {
                        $order = Order::getOrder($request->order_id, true);
                        if ($order === null) {
                            return $fail('У Вас нет доступа к указанному заказу');
                        }
                    }
                }
            ]
        ]);
        $this->setValidateAttribute([
            'order_id' => 'ID Заказа'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        return response()->json([
            'status' => 'success',
            'order' => $order
        ]);
    }

    public function byInOneClick(Request $request) {
        $this->setValidateRule([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'available_id' => 'required|integer|exists:product_availables,id',
            'phone' => 'required|phone:UA'
        ]);
        $this->setValidateAttribute([
            'product_id' => 'ID продукции',
            'quantity' => 'Количество',
            'available_id' => 'ID характеристик',
            'phone' => 'Телефон'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $order = Order::createModel('Заказ в один клик');
        if (auth()->check()) {
            $order->user_id = auth()->user()->id;
        }
        $order->phone = $request->phone;

        $save_product = [];

        $product = Product::getProduct($request->product_id);
        $available = $product->available()
            ->where('id', $request->available_id)
            ->first();

        if ($available !== null && $available->quantity > $request->quantity) {
            $price = $product->price * $request->quantity;
            $discount_price = $product->discount_price * $request->quantity;

            $save_product = [
                'product_id' => $product->id,
                'product_available_id' => $request->available_id,
                'price' => $price,
                'discount_price' => $discount_price,
                'product_price' => $product->price,
                'product_discount_price' => $product->discount_price,
                'quantity' => $request->quantity
            ];

            $available->update([
                'quantity' => $available->quantity - $request->quantity
            ]);
        }

        $bestseller = $product->bestseller()->firstOrCreate([]);
        $bestseller->update([
            'quantity' => $bestseller->quantity + $request->quantity
        ]);

        $products = $order->products()->create($save_product);
        $order->setRelation('products', $products);

        $order->save();

        $order = Order::recalculatePrice($order->fresh());

        $order->save();

        $order = Order::getOrder($order->id);

        $order->load([
            'status'
        ]);

        $this->sendToAdminCreateNotification($order);

        event(new AdminEvent('order', $order));

        return response()->json([
            'status' => 'success',
            'order' => $order
        ]);
    }
}
