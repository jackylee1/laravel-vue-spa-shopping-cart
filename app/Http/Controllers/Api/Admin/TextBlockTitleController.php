<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\TextBlockTitle;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TextBlockTitleController extends Controller
{
    use ValidateTrait;

    private function validationId() {
        $this->setValidateRule([
            'id' => 'required|integer|exists:text_block_titles,id',
        ]);
    }

    private function validationGeneration($update = false) {
        if ($update) {
            $this->validationId();
        }
        $this->setValidateRule([
            'sorting_order' => 'required|integer',
            'title' => 'required|string|max:191'
        ]);

        $this->setValidateAttribute([
            'sorting_order' => 'Порядок сортировки',
            'title' => 'Заголовок'
        ]);
    }

    public function index()
    {
        return response()->json([
            'status' => 'success',
            'text_block_titles' => TextBlockTitle::getTitles()
        ]);
    }

    public function store(Request $request)
    {
        $this->validationGeneration();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $text_block_title = TextBlockTitle::createModel();

        return response()->json([
            'status' => 'success',
            'text_block_title' => $text_block_title,
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
            'text_block_title' => TextBlockTitle::getTitle($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validationGeneration(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $text_block_title = TextBlockTitle::updateModel($id);

        return response()->json([
            'status' => 'success',
            'text_block_title' => $text_block_title,
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

        TextBlockTitle::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Запись удалена'
        ]);
    }
}
