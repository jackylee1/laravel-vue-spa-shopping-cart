<?php

namespace App\Http\Controllers\Api;

use App\Models\NovaPoshtaWarehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NovaPoshtaWarehouseController extends Controller
{
    public function get(Request $request) {
        $request->validate(['city_ref' => 'required|string|exists:nova_poshta_cities,ref']);

        return response()->json([
            'status' => 'success',
            'warehouses' => NovaPoshtaWarehouse::getItems(),
            'end' => true
        ]);
    }
}
