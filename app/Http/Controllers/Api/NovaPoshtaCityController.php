<?php

namespace App\Http\Controllers\Api;

use App\Models\NovaPoshtaCity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NovaPoshtaCityController extends Controller
{
    public function get(Request $request) {
        $request->validate(['area_ref' => 'required|string|exists:nova_poshta_areas,ref']);

        return response()->json([
            'status' => 'success',
            'cities' => NovaPoshtaCity::getItems()
        ]);
    }
}
