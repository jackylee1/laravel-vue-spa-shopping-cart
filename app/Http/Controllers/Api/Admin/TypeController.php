<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Type;
use App\Tools\File;
use App\Tools\Image;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{
    use ValidateTrait;

    private $path,
        $image_origin,
        $image_preview,
        $image_index_origin,
        $image_index_preview,
        $resize_params;

    public function __construct() {
        parent::__construct();

        $this->path = 'public/images/type/';
        $this->resize_params = [
            'width' => 300,
            'height' => 300,
            'aspect_ratio' => true,
            'crop' => true
        ];
        $this->image_origin = $this->image_preview = null;
        $this->image_index_origin = $this->image_index_preview = null;
    }

    private function validateForUpdate() {
        $this->setValidateRule([
            'id' => 'required|integer|exists:types'
        ]);
    }

    private function generateValidate($update = false) {
        $rules_slug = 'required|string|max:191|unique:types,slug';
        if ($update) {
            $this->validateForUpdate();
            $rules_slug .= ','.\request()->get('id');
        }
        $this->setValidateRule([
            'name' => 'required|string|max:191',
            'sorting_order' => 'required|integer',
            'slug' => $rules_slug,
            'image' => 'nullable|image|max:2048',
            'image_index' => 'nullable|image|max:2048',
            'show_on_footer' => 'nullable|boolean',
            'show_on_index' => 'nullable|boolean',
            'show_on_header' => 'nullable|boolean',
            'show_on_certificate' => 'nullable|boolean',
            'm_title' => 'nullable|string|max:50000',
            'm_description' => 'nullable|string|max:50000',
            'm_keyword' => 'nullable|string|max:50000',
        ]);
        $this->setValidateAttribute([
            'name' => 'Наименование',
            'slug' => 'SEO URL',
            'sorting_order' => 'Порядок сортировки',
            'show_on_index' => 'Показать на главной',
            'show_on_footer' => 'Показать в футере',
            'image' => 'Изображение',
            'show_on_certificate' => 'Показать в блоке "Сертификаты"',
            'show_on_header' => 'Отображать в шапке'
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
            'types' => Type::types(false, false)
        ]);
    }

    private function uploadImage() {
        if (request()->hasFile('image')) {
            $this->image_origin = File::upload(request(), ['file_key' => 'image', 'path_save' => $this->path]);
            $this->image_preview = File::upload(request(), ['file_key' => 'image', 'path_save' => $this->path]);

            Image::resize(
                public_path("app/{$this->image_preview->full_path}"),
                public_path("app/$this->path"),
                $this->resize_params
            );

            $this->image_origin = $this->image_origin->file_name;
            $this->image_preview = $this->image_preview->file_name;
        }

        if (request()->hasFile('image_index')) {
            $this->image_index_origin = File::upload(request(), ['file_key' => 'image_index', 'path_save' => $this->path]);
            $this->image_index_preview = File::upload(request(), ['file_key' => 'image_index', 'path_save' => $this->path]);

            Image::resize(
                public_path("app/{$this->image_index_preview->full_path}"),
                public_path("app/$this->path"),
                $this->resize_params
            );

            $this->image_index_origin = $this->image_index_origin->file_name;
            $this->image_index_preview = $this->image_index_preview->file_name;
        }
    }

    public function store(Request $request)
    {
        $this->generateValidate();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $this->uploadImage();

        $type = Type::createModel($this->image_origin, $this->image_preview, $this->image_index_origin, $this->image_index_preview);

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

    public function update(Request $request)
    {
        $this->generateValidate(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $this->uploadImage();

        $type = Type::updateModel($this->image_origin, $this->image_preview, $this->image_index_origin, $this->image_index_preview);

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
