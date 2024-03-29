<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Filter;
use App\Tools\File;
use App\Tools\Image;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FilterController extends Controller
{
    use ValidateTrait;

    private $path,
            $image_origin,
            $image_preview,
            $resize_params;

    public function __construct() {
        parent::__construct();

        $this->path = 'public/images/filter/';
        $this->resize_params = [
            'width' => 200,
            'height' => 100,
            'aspect_ratio' => true,
            'crop' => false
        ];
        $this->image_origin = $this->image_preview = null;
    }

    private function generateValidate($update = false) {
        if ($update) {
            $this->setValidateRule([
                'id' => 'required|integer|exists:filters,id',
            ]);
            if (\request()->get('parent_id') == 0) {
                $this->setValidateRule([
                    'slug' => 'required|string|unique:filters,slug,' . \request()->get('id')
                ]);
            }
        }
        else {
            if (\request()->get('parent_id') == 0) {
                $this->setValidateRule([
                    'slug' => 'required|string|unique:filters,slug'
                ]);
            }
        }

        if (\request()->get('type') === 0) {
            $this->setValidateRule([
                'parent_id' => 'required|integer|exists:filters,id'
            ]);
        }
        if (\request()->get('parent_id') != 0) {
            $this->setValidateRule([
                'parent_id' => [
                    'required',
                    'integer',
                    'exists:filters,id',
                    function($attribute, $value, $fail) {
                        if ($value === \request()->get('id')) {
                            $fail('Фильтер не может быть сама себе родительским фильтром');
                        }
                    },
                ],
            ]);
        }

        $this->setValidateRule([
            'name' => 'required|string|max:191',
            'sorting_order' => 'required|integer',
            'type' => 'required|integer|between:0,2',
            'show_on_header' => 'nullable|boolean',
            'show_on_footer' => 'nullable|boolean',
            'show_on_index' => 'nullable|boolean',
            'show_image' => 'nullable|boolean',
            'attached_filter_to_image' => 'nullable|integer|exists:filters,id'
        ]);

        $this->setValidateAttribute([
            'parent_id' => 'Родительский фильтр',
            'type' => 'Тип',
            'name' => 'Наименование',
            'sorting_order' => 'Порядок сортировки',
            'show_on_header' => 'Показать в шапке',
            'show_on_index' => 'Показать на главной',
            'show_on_footer' => 'Показать в футере',
            'show_image' => 'Отображать изображение',
            'image' => 'Изображение фильтра',
            'slug' => 'SEO URL',
            'attached_filter_to_image' => 'Прикрепленный фильтр к изображению'
        ]);
    }

    public function index() {
        return response()->json([
            'filters' => Filter::getFilters(false)
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
    }

    public function store(Request $request)
    {
        $this->generateValidate();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $this->uploadImage();

        $filter = Filter::createModel($this->image_origin, $this->image_preview);

        return response()->json([
            'status' => 'success',
            'message' => 'Фильтр создан',
            'filter' => $filter
        ]);
    }


    public function update(Request $request)
    {
        $this->generateValidate(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $this->uploadImage();

        $filter = Filter::updateModel($this->image_origin, $this->image_preview);

        return response()->json([
            'status' => 'success',
            'message' => 'Данные о фильтре изменены',
            'filter' => $filter
        ]);
    }


    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:filters,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $ids = Filter::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'remove_filters' => $ids,
            'message' => 'Фильтры и все его дочерние фильтры удалены'
        ], 200);
    }
}
