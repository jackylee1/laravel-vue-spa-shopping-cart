<?php

namespace App\Http\Controllers\Api;

use App\Models\LinkToSocialNetwork;
use App\Models\Slider;
use App\Models\TextBlockTitle;
use App\Traits\DataTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    use DataTrait;

    public function common() {
        $this->setData('link_to_social_networks', LinkToSocialNetwork::getLinks());
        $this->setData('text_pages', TextBlockTitle::getTitlesAndData());

        return response()->json($this->data);
    }

    public function index() {
        $this->setData('sliders', Slider::getAllSliders());

        return response()->json($this->data);
    }
}
