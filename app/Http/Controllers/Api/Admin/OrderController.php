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

    private function generationValidation() {

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
        //
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
