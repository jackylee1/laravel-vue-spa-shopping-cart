<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\ProductImage;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tools\File;

class ProductImageController extends Controller
{
    use ValidateTrait;

    public function update(Request $request) {
        $this->setValidateRule([
            'id' => 'required|integer|exists:product_images,id',
            'sorting_order' => 'required|integer',
            'main_status' => 'required|boolean'
        ]);
        $this->setValidateAttribute([
            'sorting_order' => 'Порядок сортировки',
            'main_status' => 'Статус изображения'
        ]);

        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $image = ProductImage::updateModel();

        return response()->json([
            'status' => 'success',
            'message' => 'Данные изображения обновлены',
            'image' => $image
        ]);
    }

    public function updatePreview(Request $request) {
        $this->setValidateRule([
            'id' => 'required|integer|exists:product_images,id',
            'image' => 'required|image|max:2048',
        ]);
        $this->setValidateAttribute([
            'image' => 'Изображение'
        ]);

        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $image = ProductImage::find(request()->get('id'));
        File::delete('/public/images/products/', $image->preview);

        $preview_image = File::upload($request, [
            'file_key' => 'image',
            'prefix_name' => 'img_',
            'path_save' => "/public/images/products/$image->product_id"
        ]);

        $image = ProductImage::updatePreviewImage("{$image->product_id}/{$preview_image->file_name}");

        return response()->json([
            'status' => 'success',
            'image' => $image,
            'message' => 'Превью изображение было изменено'
        ]);
    }
}
