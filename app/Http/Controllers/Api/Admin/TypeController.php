<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Type;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{
    use ValidateTrait;

    private function validateForUpdate() {
        $this->setValidateRule([
            'id' => 'required|integer|exists:types'
        ]);
    }

    private function generateValidate($update = false) {
        $rules_slug = 'required|string|max:255|unique:types,slug';
        if ($update) {
            $this->validateForUpdate();
            $rules_slug .= ','.\request()->get('id');
        }
        $this->setValidateRule([
            'name' => 'required|string|max:255',
            'sorting_order' => 'required|integer',
            'slug' => $rules_slug
        ]);
        $this->setValidateAttribute([
            'name' => 'Наименование',
            'slug' => 'SEO URL',
            'sorting_order' => 'Порядок сортировки'
        ]);
    }

    private function validateId($id) {
        $this->validateForUpdate();
        $validator = Validator::make(['id' => $id], $this->validate_rules);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
    }

    public function index()
    {
        return response()->json([
            'types' => Type::types()
        ]);
    }

    public function store(Request $request)
    {
        $this->generateValidate();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $type = Type::createModel();

        return response()->json([
            'status' => 'success',
            'type' => $type,
            'message' => 'Тип товара успешно создан'
        ]);
    }

    public function show($id)
    {
        $this->validateId($id);

        $type = Type::with(['categories' => function ($query) {
            $query->orderBy('sorting_order', 'asc');
        }])->find($id);

        return response()->json([
            'status' => 'success',
            'type' => $type
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->generateValidate(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $type = Type::updateModel();

        return response()->json([
            'status' => 'success',
            'type' => $type,
            'message' => 'Данные о типе товара успешно обновлены'
        ]);
    }

    public function destroy($id)
    {
        $this->validateId($id);

        Type::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Тип товара успешно удален'
        ]);
    }

    public function handleFilter(Request $request) {
        $this->setValidateRule([
            'type_id' => 'required|integer|exists:types,id',
            'filter_id' => 'required|integer|exists:filters,id'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $handle = Type::handleFilter();
        $message = 'Фильтр был удален с этого типа';
        if ($handle['operation'] === 'add') {
            $message = 'Фильтр был добавлен к этому типу';
        }

        return response()->json([
            'status' => 'success',
            'operation' => $handle['operation'],
            'create_model' => $handle['model'],
            'message' => $message
        ]);
    }
}
