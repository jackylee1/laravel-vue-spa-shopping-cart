<?php

namespace App\Http\Controllers\Api;

use App\Notifications\PublicNotification\FeedbackSendNotification;
use App\Traits\ValidateTrait;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedbackController extends Controller
{
    use ValidateTrait;

    public function send(Request $request) {
        $this->setValidateRule([
            'name' => 'required|string|max:191',
            'phone' => 'nullable|phone:UA',
            'email' => 'required|email',
            'message' => 'required|string|max:50000'
        ]);
        $this->setValidateAttribute([
            'name' => 'Имя',
            'phone' => 'Телефон',
            'email' => 'E-Mail',
            'massage' => 'Сообщение'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $user = User::getUser(1);

        $user->notify(new FeedbackSendNotification());

        return response()->json([
            'status' => 'success',
            'message' => 'Ваше сообщение успешно отправлено'
        ]);
    }
}
