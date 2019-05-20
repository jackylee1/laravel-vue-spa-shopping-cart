<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\OrderPaymentMethod;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrderPaymentMethodController extends Controller
{
    use ValidateTrait;

    private function validationId() {
        $this->setValidateRule(['id' => 'required|integer|exists:order_payment_methods']);
    }
    private function generateValidate($update = false) {
        if ($update) {
            $this->validationId();
        }

        $this->setValidateRule([
            'name' => 'required|string|max:191',
            'sorting_order' => 'required|integer'
        ]);
        $this->setValidateAttribute([
            'name' => 'Наименование',
            'sorting_order' => 'Порядок сортировки'
        ]);
    }

    public function index()
    {
        return response()->json([
            'order_payment_methods' => OrderPaymentMethod::methods()
        ]);
    }

    public function store(Request $request)
    {
        $this->generateValidate();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $method = OrderPaymentMethod::createModel();

        return response()->json([
            'status' => 'success',
            'message' => 'Метод оплаты успешно создан',
            'order_payment_method' => $method
        ]);
    }

    public function show($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:order_payment_methods,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'order_payment_method' => OrderPaymentMethod::method($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->generateValidate(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $method = OrderPaymentMethod::updateModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Данные метода оплаты успешно обновлены',
            'order_payment_method' => $method
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

        OrderPaymentMethod::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Метод оплаты был успешно удален'
        ]);
    }
}
