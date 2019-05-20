<?php

namespace App\Http\Controllers\Api;

use App\Models\NovaPoshtaArea;
use App\Http\Controllers\Controller;

class NovaPoshtaAreaController extends Controller
{
    public function get() {
        return response()->json([
            'status' => 'success',
            'areas' => NovaPoshtaArea::getItems()
        ]);
    }
}
