<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\OrderStatus;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrderStatusController extends Controller
{
    use ValidateTrait;

    private function validationId() {
        $this->setValidateRule(['id' => 'required|integer|exists:order_statuses']);
    }
    private function generateValidate($update = false) {
        if ($update) {
            $this->validationId();
            $status = OrderStatus::find(\request()->get('id'));
            $this->setValidateRule([
                'default' => [
                    'required',
                    'boolean',
                    function ($attribute, $value, $fail) use ($status) {
                        if ($status->default && \request()->get('default') == 0) {
                            $fail('Вы не можете изменить статус для "Статуса заказа" по умолчанию.');
                        }
                    }
                ]
            ]);
        } else {
            $this->setValidateRule(['default' => 'required|boolean']);
        }

        $this->setValidateRule([
            'name' => 'required|string|max:191',
            'color' => 'required|string|max:191'
        ]);
        $this->setValidateAttribute([
            'name' => 'Наименование',
            'default' => 'По умолчанию',
            'color' => 'Цвет'
        ]);
    }

    public function index()
    {
        return response()->json([
            'order_statuses' => OrderStatus::statuses()
        ]);
    }

    public function store(Request $request)
    {
        $this->generateValidate();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        if (request()->get('default') == 1) {
            OrderStatus::resetDefault();
        }

        $status = OrderStatus::createModel();

        return response()->json([
            'status' => 'success',
            'message' => 'Статус успешно успешно создан',
            'order_status' => $status
        ]);
    }

    public function show($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:order_statuses,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'order_status' => OrderStatus::getStatus($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->generateValidate(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        if (request()->get('default') == 1) {
            OrderStatus::resetDefault();
        }

        $status = OrderStatus::updateModel();

        return response()->json([
            'status' => 'success',
            'message' => 'Данные статуса успешно обновлены',
            'order_status' => $status
        ]);
    }

    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => [
                'required',
                'exists:order_statuses,id',
                function ($attribute, $value, $fail) use ($id) {
                    $model = OrderStatus::getStatus($id);
                    if ($model->default) {
                        $fail('Вы не можете удалить статус по умолчанию');
                    }
                }
            ]
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        OrderStatus::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Статус был успешно удален'
        ]);
    }
}
