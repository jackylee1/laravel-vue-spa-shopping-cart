<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\SizeTable;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SizeTableController extends Controller
{
    use ValidateTrait;

    private function validationId() {
        $this->setValidateRule([
            'id' => 'required|integer|exists:size_tables,id',
        ]);
    }

    private function validationGeneration($update = false) {
        if ($update) {
            $this->validationId();
        }
        $this->setValidateRule([
            'sorting_order' => 'required|integer',
            'title' => 'required|string|max:191',
            'description' => 'required|string|max:50000'
        ]);

        $this->setValidateAttribute([
            'sorting_order' => 'Порядок сортировки',
            'title' => 'Наименование',
            'description' => 'Описание'
        ]);
    }

    public function index()
    {
        return response()->json([
            'status' => 'success',
            'size_tables' => SizeTable::getSizes()
        ]);
    }

    public function store(Request $request)
    {
        $this->validationGeneration();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $model = SizeTable::createModel();

        return response()->json([
            'status' => 'success',
            'size_table' => $model,
            'message' => 'Таблица размеров создана'
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
            'size_table' => SizeTable::getSize($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validationGeneration(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $model = SizeTable::updateModel($id);

        return response()->json([
            'status' => 'success',
            'size_table' => $model,
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

        SizeTable::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Запись удалена'
        ]);
    }
}
