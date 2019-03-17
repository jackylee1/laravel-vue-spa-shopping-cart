<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Order;
use App\Models\ProductAvailable;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use ValidateTrait;

    private function validationId() {
        $this->setValidateRule([
            'id' => 'required|integer|exists:orders,id'
        ]);
    }

    public function index()
    {
        return response()->json([
            'status' => 'success',
            'orders' => Order::orders()
        ]);
    }

    public function store(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Заказ успешно создан',
            'order' => Order::createModel()
        ]);
    }

    public function show($id)
    {
        $this->validationId();
        $validator = Validator::make(['id' => $id], $this->validate_rules);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $order = Order::getOrder($id);

        return response()->json([
            'status' => 'success',
            'order' => $order
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validationId();
        $this->setValidateRule([
            'user_id' => 'nullable|integer|exists:users,id',
            'user_name' => 'nullable|string|max:191',
            'user_surname' => 'nullable|string|max:191',
            'user_patronymic' => 'nullable|string|max:191',
            'phone' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'note' => 'nullable|string|max:50000',
            'order_payment_method_id' => 'required|integer|exists:order_payment_methods,id',
            'order_status_id' => 'required|integer|exists:order_statuses,id',
        ]);
        $this->setValidateAttribute([
            'user_id' => 'Пользователь',
            'user_name' => 'Имя',
            'user_surname' => 'Фамилия',
            'user_patronymic' => 'Отвество',
            'phone' => 'Телефон',
            'email' => 'E-Mail',
            'note' => 'Комментарий',
            'order_payment_method_id' => 'Метод оплаты',
            'order_status_id' => 'Статус'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $order = Order::updateModel();

        return response()->json([
            'status' => 'success',
            'message' => 'Данные были успешно обновлены',
            'order' => $order
        ]);
    }

    public function destroy($id)
    {
        $this->validationId();
        $validator = Validator::make(['id' => $id], $this->validate_rules);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        Order::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Заказ был успешно удален'
        ]);
    }

    public function addProduct(Request $request) {
        $this->setValidateRule([
            'order_id' => 'required|integer|exists:orders,id',
            'product_id' => 'required|integer|exists:products,id',
            'product_available_id' => 'required|integer|exists:product_availables,id',
            'quantity' => [
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $available = ProductAvailable::getItem(\request()->get('product_available_id'));
                    if ($available->product_id != \request()->get('product_id')) {
                        return $fail('Эта опция не пренадлежит указанному товару');
                    }
                    if ($available->quantity < $value) {
                        return $fail('Вы передали не допустимое количество товара по данной опции');
                    }
                }
            ]
        ]);
        $this->setValidateAttribute([
            'order_id' => 'Заказ',
            'product_id' => 'Продукция',
            'product_available_id' => 'Наличие',
            'quantity' => 'Количество',
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $order = Order::addProduct();

        return response()->json([
            'status' => 'success',
            'message' => 'Товар был добавлен в заказ',
            'order' => $order,
        ]);
    }

    public function deleteProduct(Request $request) {
        $this->setValidateRule([
            'order_id' => 'required|integer|exists:orders,id',
            'order_product_id' => 'required|integer|exists:order_products,id'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $operation = Order::deleteProduct();

        return response()->json([
            'status' => 'success',
            'message' => 'Товар был удален с заказа',
            'order' => $operation['order'],
            'order_product' => $operation['order_product']
        ]);
    }

    public function deleteStatus(Request $request) {
        $this->setValidateRule([
            'order_id' => 'required|integer|exists:orders,id',
            'order_status_id' => [
                'required',
                'integer',
                'exists:order_history_statuses,id',
                function ($attribute, $value, $fail) {
                    $first_status = Order::getFirstStatus();
                    if ($first_status->id === $value) {
                        return $fail('Вы не можете удалить первый статус заказа');
                    }
                }
            ]
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $order = Order::deleteStatus();

        return response()->json([
            'status' => 'success',
            'message' => 'Статус был успешно удален',
            'order' => $order,
        ]);
    }
}
