<?php

namespace App\Http\Controllers;

use App\Base\PublicControllerBase;
use App\Models\LinkToSocialNetwork;
use App\Models\Slider;
use App\Traits\DataTrait;
use Illuminate\Http\Request;

class PageController extends PublicControllerBase
{
    use DataTrait;

    public function index() {
        $this->setData([
            ['sliders', Slider::getAllSliders()],
            ['link_to_social_networks', LinkToSocialNetwork::getLinks()],
        ]);

        return view('index', $this->data);
    }
}