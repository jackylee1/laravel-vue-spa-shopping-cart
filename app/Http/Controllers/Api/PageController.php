<?php

namespace App\Http\Controllers\Api;

use App\Models\Slider;
use App\Traits\DataTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    use DataTrait;

    public function index() {
        $this->setData([
            ['sliders', Slider::getAllSliders()]
        ]);

        return response()->json($this->data);
    }
}
