<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Category;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use ValidateTrait;

    private function generateValidate($update = false) {
        if ($update) {
            $this->setValidateRule([
                'id' => 'required|integer|exists:categories,id',
            ]);
        }

        $this->setValidateRule([
            'slug' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (Category::checkUniqueSlug() > 0) {
                        $fail('У этого типа уже существует категория с указанным SEO URL');
                    }
                }
            ]
        ]);

        if (\request()->get('parent_id') !== 0) {
            $this->setValidateRule([
                'parent_id' => [
                    'required',
                    'integer',
                    'exists:categories,id',
                    function($attribute, $value, $fail) {
                        if ($value === \request()->get('id')) {
                            $fail('Категория не может быть сама себе родительской категорией');
                        }
                    },
                ],
            ]);
        }

        $this->setValidateRule([
            'type_id' => 'required|integer|exists:types,id',
            'name' => 'required|string|max:191',
            'sorting_order' => 'required|integer',
            'show_on_header' => 'nullable|boolean',
            'hidden_name' => 'nullable|boolean',
            'active_link' => 'nullable|boolean',
            'm_title' => 'nullable|string|max:50000',
            'm_description' => 'nullable|string|max:50000',
            'm_keyword' => 'nullable|string|max:50000',
        ]);

        $this->setValidateAttribute([
            'parent_id' => 'Родительская категория',
            'type_id' => 'Тип',
            'hidden_name' => 'Скрыть наименование',
            'name' => 'Наименование',
            'sorting_order' => 'Порядок сортировки',
            'slug' => 'SEO адрес',
            'active_link' => 'Активная ссылка'
        ]);
    }

    public function store(Request $request)
    {
        $this->generateValidate();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

       $category = Category::createModel();

        return response()->json([
            'status' => 'success',
            'message' => 'Категория создана',
            'category' => $category
        ]);
    }


    public function update(Request $request, $id)
    {
        $this->generateValidate(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $category = Category::updateModel();

        return response()->json([
            'status' => 'success',
            'message' => 'Данные о категории изменены',
            'category' => $category
        ]);
    }


    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $ids = Category::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'remove_categories' => $ids,
            'message' => 'Категория и ее подкатегории удалены'
        ], 200);
    }

    public function handleFilter(Request $request) {
        $this->setValidateRule([
            'category_id' => 'required|integer|exists:categories,id',
            'filter_id' => 'required|integer|exists:filters,id'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $handle = Category::handleFilter();
        $message = 'Фильтр был удален с этой категории';
        if ($handle['operation'] === 'add') {
            $message = 'Фильтр был добавлен к этой категории';
        }

        return response()->json([
            'status' => 'success',
            'operation' => $handle['operation'],
            'create_model' => $handle['model'],
            'message' => $message
        ]);
    }
}
