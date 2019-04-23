<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\PromotionalCode;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    use ValidateTrait;

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
        $order->user_patronymic = $cart->user_patronymic;
        $order->phone = $cart->phone;
        $order->email = $cart->email;
        $order->delivery_method = $cart->delivery;
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

            if ($available->quantity > $cart_product->quantity) {
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
            }
        });

        $order->save();

        $products = $order->products()->createMany($products);
        $order->setRelation('products', $products);

        $order = Order::recalculatePrice($order, ($promotional_code !== null) ? $promotional_code->discount: 0);

        $order->save();

        $cart->user_promotional_code_id = null;
        $cart->products()->delete();
        $cart->save();

        return response()->json([
            'status' => 'success',
            'cart' => Cart::firstOrCreateModel()
        ]);
    }
}
