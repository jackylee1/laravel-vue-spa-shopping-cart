<?php

namespace App\Http\Controllers;

use App\Events\AdminEvent;
use App\Models\Subscribe;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    use ValidateTrait;

    public function store(Request $request) {
        $this->setValidateRule([
            'email' => 'required|email|unique:subscribes'
        ]);
        $this->setValidateAttribute([
            'email' => 'E-Mail'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $subscribe = Subscribe::createModel();

        broadcast(new AdminEvent('subscribe', $subscribe));

        return response()->json([
            'status' => 'success',
            'message' => 'На Ваш E-Mail успешно оформлена подписка'
        ]);
    }
}
