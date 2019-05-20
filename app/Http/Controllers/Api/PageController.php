<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Favorite;
use App\Models\Filter;
use App\Models\IndexMediaFile;
use App\Models\LinkToSocialNetwork;
use App\Models\OrderPaymentMethod;
use App\Models\Product;
use App\Models\Setting;
use App\Models\SizeTable;
use App\Models\Slider;
use App\Models\TextBlockTitle;
use App\Models\Type;
use App\Models\UtfRecord;
use App\Traits\DataTrait;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    use DataTrait,
        ValidateTrait;

    public function common(Request $request) {
        $this->setValidateRule([
            'cart_key' => 'nullable|string',
            'favorite_key' => 'nullable|string',
        ]);
        $this->setValidateAttribute([
            'cart_key' => 'Ключ к корзине',
            'favorite_key' => 'Ключ к избранным товарам',
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        if ($request->filled('cart_key')) {
            $cart = Cart::getByKey($request->get('cart_key'));
            if ($cart === null) {
                $request->merge(['cart_key' => uniqid()]);
            }
        }

        if ($request->filled('favorite_key')) {
            $favorite = Favorite::getByKey($request->get('favorite_key'));
            if ($favorite === null) {
                $request->merge(['favorite_key' => uniqid()]);
            }
        }

        $request->merge(request()->all());
        app()->instance('request', $request);

        $this->setData('link_to_social_networks', LinkToSocialNetwork::getLinks());
        $this->setData('text_pages', TextBlockTitle::getTitlesAndData());
        $this->setData('types', Type::types());
        $this->setData('filters', Filter::getFilters());
        $this->setData('size_tables', SizeTable::getSizes());
        $this->setData('new_products', Product::getNewProducts());
        $this->setData('bestseller_products', Product::getBestsellers());
        $this->setData('cart', Cart::getItem());
        $this->setData('favorite', Favorite::getItem());
        $this->setData('payment_methods', OrderPaymentMethod::methods());
        $this->setData('utf_records', UtfRecord::getItems());
        $this->setData('settings', Setting::getItems());
        $this->setData('index_media_files', IndexMediaFile::getItems());

        if (auth()->check()) {
            $this->setData('user', auth()->user());
        }

        return response()->json($this->data);
    }

    public function index() {
        $this->setData('sliders', Slider::getAllSliders());

        return response()->json($this->data);
    }
}
