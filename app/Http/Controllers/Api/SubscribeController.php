<?php

namespace App\Http\Controllers\Api;

use App\Events\AdminEvent;
use App\Models\Subscribe;
use App\Notifications\Admin\SendSubscribeNotification;
use App\Traits\ValidateTrait;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        User::getUser(1)->notify(new SendSubscribeNotification($subscribe->id));
        event(new AdminEvent('subscribe', $subscribe));

        return response()->json([
            'status' => 'success',
            'message' => 'На Ваш E-Mail успешно оформлена подписка'
        ]);
    }
}
