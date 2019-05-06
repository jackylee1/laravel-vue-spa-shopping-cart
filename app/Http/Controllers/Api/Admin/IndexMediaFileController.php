<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\IndexMediaFile;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class IndexMediaFileController extends Controller
{
    use ValidateTrait;

    private function validationId() {
        $this->setValidateRule([
            'id' => 'required|integer|exists:index_media_files,id',
        ]);
    }

    private function validationGeneration($update = false) {
        if ($update) {
            $this->validationId();
        }
        $this->setValidateRule([
            'sorting_order' => 'required|integer',
            'video' => 'required|string|max:191'
        ]);

        $this->setValidateAttribute([
            'sorting_order' => 'Порядок сортировки',
            'title' => 'Ссылка на Youtube видео'
        ]);
    }

    public function index()
    {
        return response()->json([
            'status' => 'success',
            'index_media_files' => IndexMediaFile::getItems()
        ]);
    }

    public function store(Request $request)
    {
        $this->validationGeneration();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $index_media_file = IndexMediaFile::createModel();

        return response()->json([
            'status' => 'success',
            'index_media_file' => $index_media_file,
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
            'index_media_file' => IndexMediaFile::getItem($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validationGeneration(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $index_media_file = IndexMediaFile::updateModel($id);

        return response()->json([
            'status' => 'success',
            'index_media_file' => $index_media_file,
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

        IndexMediaFile::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Запись удалена'
        ]);
    }
}
