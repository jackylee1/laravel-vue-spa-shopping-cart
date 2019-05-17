<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Order;
use App\Models\PromotionalCode;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PromotionalCodeController extends Controller
{
    use ValidateTrait;

    private function validateForUpdate() {
        $this->setValidateRule([
            'id' => 'required|integer|exists:promotional_codes',
            'code' => 'required|string|unique:promotional_codes,code,'.\request()->get('id')
        ]);
    }

    private function generateValidate($update = false) {
        if ($update) {
            $this->validateForUpdate();
        }
        else {
            $this->setValidateRule([
                'code' => 'required|string|unique:promotional_codes,code',
                'type' => 'required|integer|in:0,1'
            ]);
        }
        if (\request()->get('type') === 0) {
            $this->setValidateRule([
                'discount' => 'required|integer|between:0,100'
            ]);
        }
        else {
            $this->setValidateRule([
                'cash_value' => 'required|integer'
            ]);
        }
        $this->setValidateRule([
            'status' => 'required|boolean'
        ]);
        $this->setValidateAttribute([
            'name' => 'Имя',
            'code' => 'Промо-код',
            'status' => 'Статус',
            'type' => 'Тип',
            'cash_value' => 'Денежное значение',
            'discount' => 'Процент скидки'
        ]);
    }

    public function index(Request $request)
    {
        $this->setValidateRule([
            'q' => 'nullable|string|max:191',
            'status' => [
                'nullable',
                function($attribute, $value, $fail) {
                    if (!in_array($value, ['all', 0, 1])) {
                        $fail($attribute.' должен иметь значение "all" или болевое значение.');
                    }
                },
            ],
            'user_id' => 'nullable|integer|exists:users,id'
        ]);
        $this->setValidateAttribute([
            'q' => 'Текст запроса',
            'status' => 'Статус промокода'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $promotional_codes = PromotionalCode::promotionalCodes();

        return response()->json([
            'promotional_codes' => $promotional_codes
        ]);
    }

    public function show($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:promotional_codes'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        return response()->json([
            'promotional_code' => PromotionalCode::find($id)
        ]);
    }

    public function store(Request $request)
    {
        $this->generateValidate();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $promotional_code = PromotionalCode::createModel();

        return response()->json([
            'status' => 'success',
            'promotional_code' => $promotional_code,
            'message' => 'Промо-код успешно создан'
        ]);
    }


    public function update(Request $request, $id)
    {
        $this->generateValidate(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $promotional_code = PromotionalCode::updateModel();

        return response()->json([
            'status' => 'success',
            'message' => 'Данные Промокода успешно изменены',
            'promotional_code' => $promotional_code
        ]);
    }

    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:promotional_codes,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        PromotionalCode::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Промо-код успешно удален',
            'order' => Order::resetPromotionalCode($id)
        ]);
    }

    private function generateCode() {
        return str_random(8);
    }

    private function checkFreeCode($code) {
        if (PromotionalCode::getModelByCode($code) === null) {
            return $code;
        }
        else {
            $this->checkFreeCode($this->generateCode());
        }
    }

    public function getFree() {
        $code = $this->checkFreeCode($this->generateCode());

        return response()->json([
            'status' => 'success',
            'promotional_code' => $code
        ]);
    }
}
