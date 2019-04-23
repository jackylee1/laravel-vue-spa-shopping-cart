<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\ProductAvailable;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductAvailableController extends Controller
{
    use ValidateTrait;

    public function create(Request $request) {
        $this->setValidateRule([
            'product_id' => 'required|integer|exists:products,id',
            'filters' => 'required|array',
            'filters.*' => 'integer|exists:filters,id',
            'quantity' => 'required|integer|between:0,999999'
        ]);
        $this->setValidateAttribute([
            'filters' => 'Фильтра',
            'quantity' => 'Количество'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $available = ProductAvailable::createModel();

        return response()->json([
            'status' => 'success',
            'message' => 'Количество товара было добавлено',
            'available' => $available
        ]);
    }

    public function updateQuantity(Request $request) {
        $this->setValidateRule([
            'id' => 'required|integer|exists:product_availables,id',
            'quantity' => 'required|integer|between:0,999999'
        ]);
        $this->setValidateAttribute([
            'quantity' => 'Количество'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        ProductAvailable::updateQuantity();

        return response()->json([
            'status' => 'success',
            'message' => 'Количество в наличии было обновлено'
        ]);
    }

    public function destroy(Request $request) {
        $this->setValidateRule([
            'id' => 'required|integer|exists:product_availables,id',
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        ProductAvailable::destroyModel();

        return response()->json([
            'status' => 'success',
            'message' => 'Запись удалена'
        ]);
    }
}
