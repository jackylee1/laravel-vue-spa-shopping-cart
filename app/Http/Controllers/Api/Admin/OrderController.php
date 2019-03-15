<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Order;
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
            'user_name' => 'nullable|string|max:191',
            'user_surname' => 'nullable|string|max:191',
            'user_patronymic' => 'nullable|string|max:191',
            'phone' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'note' => 'nullable|string|max:50000',
        ]);
        $this->setValidateAttribute([
            'user_name' => 'Имя',
            'user_surname' => 'Фамилия',
            'user_patronymic' => 'Отвество',
            'phone' => 'Телефон',
            'email' => 'E-Mail',
            'note' => 'Комментарий',
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
}
