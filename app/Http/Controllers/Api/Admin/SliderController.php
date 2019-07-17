<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Slider;
use App\Tools\File;
use App\Tools\Image;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    use ValidateTrait;

    private $path,
            $resize_params;

    public function __construct() {
        parent::__construct();

        $this->path = 'public/images/slider/';
        $this->resize_params = [
            'width' => 300,
            'height' => 300,
            'aspect_ratio' => true,
            'crop' => false
        ];
    }

    private function validationId() {
        $this->setValidateRule([
            'id' => 'required|integer|exists:sliders,id'
        ]);
    }

    private function validationGeneration($update = false) {
        if ($update) {
            $this->validationId();
        }

        $this->setValidateRule([
            'title' => 'required|string|max:191',
            'description' => 'nullable|string|max:50000',
            'url' => 'nullable|url',
            'image' => ($update) ? 'nullable' : 'required' . '|image|max:2048',
            'title_align' => 'nullable|string|in:center,left,right',
            'btn_align' => 'nullable|string|in:center,left,right',
        ]);

        $this->setValidateAttribute([
            'title' => 'Заголовок',
            'description' => 'Описание',
            'url' => 'Ссылка на кнопке',
            'image' => 'Изображение'
        ]);
    }

    public function index()
    {
        return response()->json([
            'status' => 'success',
            'sliders' => Slider::getSliders()
        ]);
    }

    public function store(Request $request)
    {
        $this->validationGeneration();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $image_origin = File::upload($request, ['file_key' => 'image', 'path_save' => $this->path]);
        $image_preview = File::upload($request, ['file_key' => 'image', 'path_save' => $this->path]);

        Image::resize(public_path("app/$image_preview->full_path"), public_path("app/$this->path"), $this->resize_params);

        $slider = Slider::createModel($image_origin->file_name, $image_preview->file_name);

        return response()->json([
            'status' => 'success',
            'slider' => $slider,
            'image' => str_replace('public', '', $this->path.$image_preview->file_name),
            'message' => 'Слайд успешно создан'
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
            'slider' => Slider::getSlider($id)
        ]);
    }

    public function update(Request $request)
    {
        $this->validationGeneration(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $image_origin = $image_preview = null;

        if ($request->hasFile('image')) {
            $image_origin = File::upload($request, ['file_key' => 'image', 'path_save' => $this->path]);
            $image_preview = File::upload($request, ['file_key' => 'image', 'path_save' => $this->path]);

            Image::resize(public_path("app/$image_preview->full_path"), public_path("app/$this->path"), $this->resize_params);

            $image_origin = $image_origin->file_name;
            $image_preview = $image_preview->file_name;
        }

        $slider = Slider::updateModel($image_origin, $image_preview);

        if ($slider->image_preview !== null) {
            $image_preview = str_replace('public', '', $this->path.$slider->image_preview);
        }

        return response()->json([
            'status' => 'success',
            'slider' => $slider,
            'image' => $image_preview
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

        Slider::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Слайд был удален'
        ]);
    }
}
