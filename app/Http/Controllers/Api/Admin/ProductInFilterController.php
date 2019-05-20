<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\ProductInFilter;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductInFilterController extends Controller
{
    use ValidateTrait;

    public function remove(Request $request) {
        $this->setValidateRule([
            'id' => 'required|integer|exists:product_in_filters,id'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        ProductInFilter::destroyModel($request->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Товар был удален с указанного типа (категории|фильтра)'
        ]);
    }
}
