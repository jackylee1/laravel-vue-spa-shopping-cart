<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Product;
use App\Tools\File;
use App\Tools\DateTimeTools;
use App\Tools\Image;
use App\Traits\ImageTrait;
use App\Traits\ValidateTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use ImageTrait;
    use ValidateTrait;

    private $path,
            $resize_params;

    public function __construct() {
        parent::__construct();

        $this->path = 'public/images/products/';
        $this->resize_params = [
            'width' => 170,
            'height' => 190,
            'aspect_ratio' => true,
            'crop' => true
        ];
    }

    private function generateValidate($update = false) {
        if ($update) {
            $this->setValidateRule([
                'id' => 'required|integer|exists:products,id',
                'slug' => 'required|string|unique:products,slug,'.\request()->get('id'),
                'article' => 'required|string|unique:products,article,'.\request()->get('id'),
            ]);
        }
        else {
            $this->setValidateRule([
                'slug' => 'required|string|unique:products,slug',
                'article' => 'required|string|unique:products,article',
            ]);
        }
        $this->setValidateRule([
            'name' => 'required|string|max:191',
            'description' => 'required|string|max:10000',
            'preview_description' => 'required|string|max:2000',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'discount_price' => [
                'nullable',
                'regex:/^\d+(\.\d{1,2})?$/',
                function ($attribute, $value, $fail) {
                    if ($value > request()->get('price')) {
                        return $fail('Акционная цена должна быть меньше Цены товара');
                    }
                    if ($value == 0) {
                        return $fail('Акционная цена должна быть больше нуля');
                    }
                },
                'required_with:discount_start,discount_end'
            ],
            'discount_start' => 'nullable|datetime|required_with:discount_price',
            'discount_end' => 'nullable|datetime|required_with:discount_price',
            'status' => 'required|boolean',
            'date_inclusion' => 'nullable|date',
        ]);
        if (request()->filled('discount_start') && request()->filled('discount_end')) {
            $start_date = DateTimeTools::explodeRequestDateTime(request()->get('discount_start'));
            $end_date = DateTimeTools::explodeRequestDateTime(request()->get('discount_end'));

            $start = Carbon::parse("{$start_date->date} {$start_date->time}");
            $end = Carbon::parse("{$end_date->date} {$end_date->time}");

            $this->setValidateRule([
                'discount_start' => [
                    function ($attribute, $value, $fail) use ($start, $end) {
                        if ($start->gt($end)) {
                            $fail('"Дата начала" не может быть больше "Даты окончания" скидки.');
                        }
                    }
                ],
                'discount_end' => [
                    function ($attribute, $value, $fail) use ($start, $end) {
                        if ($end->lt($start)) {
                            $fail('"Дата/время окончания скидки" не может быть меньше "Дата/время начала скидки".');
                        }
                        if ($end->lt(Carbon::now())) {
                            $fail('"Дата/время окончания скидки" не может быть меньше текущей даты.');
                        }
                    }
                ]
            ]);
        }
        $this->setValidateAttribute([
            'slug' => 'SEO URL',
            'article' => 'Артикул',
            'name' => 'Наименование',
            'description' => 'Описание',
            'preview_description' => 'Краткое описания',
            'price' => 'Цена',
            'discount_price' => 'Акционная цена',
            'discount_start' => 'Дата/время начала скидки',
            'discount_end' => 'Дата/время окончания скидки',
            'status' => 'Статус',
            'date_inclusion' => 'Дата включения',
        ]);
    }

    public function index()
    {
        $validator = Validator::make(\request()->all(), [
            'q' => 'nullable|string',
            'selected_type' => 'nullable|integer|exists:types,id',
            'selected_categories' => 'nullable|array',
            'selected_categories.*' => 'nullable|integer|exists:categories,id',
            'selected_filters' => 'nullable|array',
            'selected_filters.*' => 'nullable|integer|exists:filters,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $products = Product::getProducts();

        return response()->json([
            'products' => $products
        ]);
    }

    public function show($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:products,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        return response()->json([
            'status' => 'success',
            'product' => Product::getProduct($id)
        ]);
    }


    public function store(Request $request)
    {
        $this->generateValidate();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $product = Product::createModel();

        return response()->json([
            'status' => 'success',
            'product' => $product,
            'message' => 'Продукт успешно создан'
        ]);
    }


    public function update(Request $request, $id)
    {
        $this->generateValidate(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $product = Product::updateModel();

        return response()->json([
            'status' => 'success',
            'product' => $product,
            'message' => 'Данные о продукте успешно обновлены'
        ]);
    }

    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:products,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        Product::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Продукт успешно удален'
        ]);
    }

    public function uploadImage(Request $request) {
        $this->setValidateRule([
            'image' => 'required|mimes:jpeg,png,jpg,gif|max:2048',
            'product_id' => 'required|integer|exists:products,id',
            'sorting_order' => 'nullable|integer'
        ]);
        $this->setValidateAttribute([
            'image' => 'Изображение'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $this->path = "{$this->path}$request->product_id/";

        $image_origin = File::upload($request, ['file_key' => 'image', 'path_save' => $this->path]);
        $image_preview = File::upload($request, ['file_key' => 'image', 'path_save' => $this->path]);

        Image::resize(public_path("app/$image_preview->full_path"), public_path("app/$this->path"), $this->resize_params);

        $product_image = Product::createImage($image_origin->file_name, $image_preview->file_name);

        return response()->json([
            'status' => 'success',
            'product_image' => $product_image,
            'image_name' => $image_preview->file_name,
            'image_url' => "/app/{$this->path}{$image_preview->file_name}",
            'message' => 'Изображение успешно добавлено'
        ]);
    }

    public function deleteImage(Request $request) {
        $this->setValidateRule([
            'product_id' => 'required|integer|exists:products,id',
            'image_id' => 'required|integer|exists:product_images,id',
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        Product::deleteImage();

        return response()->json(['status' => 'success', 'message' => 'Изображение удалено']);
    }

    public function addFilter(Request $request) {
        $this->setValidateRule([
            'product_id' => 'required|integer|exists:products,id',
            'type_id' => 'required|integer|exists:types,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'filter_id' => 'nullable|integer|exists:filters,id',
            'categories' => 'nullable|array',
            'categories.*' => 'nullable|integer|exists:categories,id',
            'filters' => 'nullable|array',
            'filters.*' => 'nullable|integer|exists:filters,id'
        ]);
        $this->setValidateAttribute([
            'type_id' => 'Тип',
            'category_id' => 'Категория',
            'filter_id' => 'Фильтр',
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $filter = Product::addToFilter();

        return response()->json([
            'status' => 'success',
            'filter' => $filter,
            'message' => 'Товар был добавлен к типу товара (категории|фильтру)'
        ]);
    }
}