<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\UtfRecord;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UtfRecordController extends Controller
{
    use ValidateTrait;

    private function validationId() {
        $this->setValidateRule([
            'id' => 'required|integer|exists:utf_records,id',
        ]);
    }

    private function validationGeneration($update = false) {
        if ($update) {
            $this->validationId();
        }
        $this->setValidateRule([
            'sorting_order' => 'required|integer',
            'description' => 'required|string|max:191'
        ]);

        $this->setValidateAttribute([
            'sorting_order' => 'Порядок сортировки',
            'description' => 'Описание'
        ]);
    }

    public function index()
    {
        return response()->json([
            'status' => 'success',
            'utf_records' => UtfRecord::getItems()
        ]);
    }

    public function store(Request $request)
    {
        $this->validationGeneration();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $utf_record = UtfRecord::createModel();

        return response()->json([
            'status' => 'success',
            'utf_record' => $utf_record,
            'message' => 'Запись успешно создана'
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

        return response()->json([
            'status' => 'success',
            'utf_record' => UtfRecord::getTitle($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validationGeneration(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $utf_record = UtfRecord::updateModel($id);

        return response()->json([
            'status' => 'success',
            'utf_record' => $utf_record,
            'message' => 'Данные успешно обновлены'
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

        UtfRecord::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Запись удалена'
        ]);
    }
}
