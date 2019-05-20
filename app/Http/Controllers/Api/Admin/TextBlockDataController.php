<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\TextBlockData;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TextBlockDataController extends Controller
{
    use ValidateTrait;

    private function validationId() {
        $this->setValidateRule([
            'id' => 'required|integer|exists:text_block_datas,id'
        ]);
    }

    private function generationValidation($update = false) {
        if ($update) {
            $this->validationId();
        }
        $this->setValidateRule([
            'title' => 'required|string|max:191',
            'type' => 'required|integer|between:0,1',
            'url' => (request()->get('type') == 1) ? 'required|' : 'nullable|' . 'url|max:191',
            'description' => 'nullable|string|max:50000',
            'sorting_order' => 'required|integer',
            'm_title' => 'nullable|string|max:50000',
            'm_description' => 'nullable|string|max:50000',
            'm_keyword' => 'nullable|string|max:50000',
        ]);
        if (request()->get('type') == 0) {
            $slug_rules = 'required|string|max:191|unique:text_block_datas,slug';
            if ($update) {
                $slug_rules .= ',' . \request()->get('id');
            }
            $this->setValidateRule([
                'slug' => $slug_rules
            ]);
        }

        $this->setValidateAttribute([
            'title' => 'Заголовок',
            'type' => 'Тип',
            'url' => 'Ссылка',
            'description' => 'Описание',
            'sorting_order' => 'Порядок сортировки',
            'slug' => 'SEO URL'
        ]);
    }

    public function index()
    {
        return response()->json([
            'text_block_data' => TextBlockData::getItems()
        ]);
    }

    public function store(Request $request)
    {
        $this->generationValidation();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $text_block_data = TextBlockData::createModel();

        return response()->json([
            'status' => 'success',
            'text_block_data' => $text_block_data,
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
            'text_block_data' => TextBlockData::getData($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->generationValidation(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $text_block_data = TextBlockData::updateModel($id);

        return response()->json([
            'status' => 'success',
            'text_block_data' => $text_block_data,
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

        TextBlockData::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Запись удалена'
        ]);
    }
}
