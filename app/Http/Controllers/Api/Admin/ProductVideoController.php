<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\ProductVideo;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductVideoController extends Controller
{
    use ValidateTrait;

    private function validationId() {
        $this->setValidateRule([
            'id' => 'required|integer|exists:product_videos,id',
        ]);
    }

    private function validationGeneration($update = false) {
        if ($update) {
            $this->validationId();
        }
        $this->setValidateRule([
            'sorting_order' => 'required|integer',
            'url' => 'required|url|max:191',
            'product_id' => 'required|integer|exists:products,id'
        ]);

        $this->setValidateAttribute([
            'sorting_order' => 'Порядок сортировки',
            'url' => 'Ссылка на Youtube видео'
        ]);
    }

    public function store(Request $request)
    {
        $this->validationGeneration();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $product_video = ProductVideo::createModel();

        return response()->json([
            'status' => 'success',
            'product_video' => $product_video,
            'message' => 'Ссылка на видео была добавлена к товару'
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validationGeneration(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $product_video = ProductVideo::updateModel($id);

        return response()->json([
            'status' => 'success',
            'product_video' => $product_video,
            'message' => 'Данные о ссылке на видео были обновлены'
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

        ProductVideo::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Ссылка на видео была удалена'
        ]);
    }
}
