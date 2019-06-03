<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscribe;
use App\Notifications\PublicNotification\MailResetPasswordToken;
use App\Traits\ValidateTrait;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    use ValidateTrait,
        ResetsPasswords;

    private function setRegex($password = 'password', $confirmation = 'password_confirmation') {
        $password_regex_message = 'Пароль имеет не верный формат. Пароль должен состоять как минимум с 3 символов латинского алфавита включа один символ в верхнем регистре и 3 цифр.';
        $this->validate_messages["{$password}.regex"] = $password_regex_message;
        $this->validate_messages["{$confirmation}.regex"] = $password_regex_message;

        return 'regex:/^.*(?=.{3,})(?=.*[a-z,A-Z])(?=.*[0-9])(?=.*[\d\X]).*$/';
    }

    public function update(Request $request, $id)
    {
        $password_regex = $this->setRegex('new_password', 'new_password_confirmation');
        $this->setValidateRule([
            'old_password' => [
                'nullable',
                'required_with:new_password',
                function ($attribute, $value, $fail) {
                    if (!Hash::check(request()->get('old_password'), auth()->user()->password)) {
                        $fail('Некоректный старый пароль');
                    }
                }
            ],
            'new_password' => "nullable|required_with:new_password_confirmation|required_with:old_password|confirmed|min:6|$password_regex",
            'new_password_confirmation' => "nullable|required_with:new_password|min:6|$password_regex"
        ]);

        $this->setValidateRule([
            'name' => 'required|string|max:50',
            'surname' => 'required|string|max:50',
            'phone' => 'required|phone:UA|unique:users,phone,' . auth()->user()->id,
            'email' => 'required|email|unique:users,email,' . auth()->user()->id
        ]);

        $this->setValidateAttribute([
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'phone' => 'Телефон',
            'email' => 'E-Mail',
            'old_password' => 'Старый пароль',
            'new_password' => 'Новый пароль',
            'new_password_confirmation' => 'Подтвердите пароль'
        ]);
        $request->validate($this->validate_rules, $this->validate_messages, $this->validate_attributes);

        $user = User::find(auth()->user()->id);

        $user->update([
            'user_name' => $request->name,
            'user_surname' => $request->surname,
            'phone' => $request->phone,
            'email' => $request->email
        ]);

        $token = null;

        if ($request->filled('new_password')) {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            if (!$token = auth('api')->attempt(['email' => $request->email, 'password' => $request->new_password])) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'access_token' => $token
        ]);
    }

    public function registration(Request $request) {
        $password_regex = $this->setRegex('password', 'password_confirmation');
        $this->setValidateRule([
            'name' => 'required|string|max:50',
            'surname' => 'required|string|max:50',
            'phone' => 'required|phone:UA',
            'email' => 'required|email|unique:users,email',
            'password' => "required|required_with:password_confirmation|confirmed|min:6|$password_regex",
            'password_confirmation' => "required|required_with:password|min:6|$password_regex",
            'subscribe' => 'nullable|boolean'
        ]);
        $this->setValidateAttribute([
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'phone' => 'Телефон',
            'email' => 'E-Mail',
            'password' => 'Пароль',
            'password_confirmation' => 'Подтверждение пароля'
        ]);
        $request->validate($this->validate_rules, $this->validate_messages, $this->validate_attributes);

        $user = User::registration();

        if ($request->filled('subscribe') && $request->subscribe) {
            $subscribe = Subscribe::firstOrCreateModel($user->email);
        }

        $token = auth('api')->attempt(['email' => $user->email, 'password' => $request->password]);

        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'user' => $user,
            'token_type' => 'bearer',
            'remember' => true,
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function sendResetPassword(Request $request) {
        $this->setValidateRule(['email' => 'required|email|exists:users,email']);
        $this->setValidateAttribute([
            'email' => 'E-Mail'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $user = User::where('email', request()->input('email'))->first();
        $token = Password::getRepository()->create($user);

        $user->notify(new MailResetPasswordToken($token, $user));

        return response()->json([
            'status' => 'success',
            'message' => 'Инструкция по восстановлению пароля отправлена на указанный E-Mail'
        ]);
    }

    public function resetUserPassword(Request $request)
    {
        $password_regex = $this->setRegex('password', 'password_confirmation');
        $this->setValidateRule([
            'token' => 'required',
            'email' => 'required|email',
            'password' => "required|confirmed|min:6|$password_regex",
        ]);
        $this->setValidateAttribute([
            'token' => 'Токен',
            'email' => 'E-Mail',
            'password' => 'Новый пароль',
            'password_confirmation' => 'Подтвердите новый пароль'
        ]);
        $request->validate($this->validate_rules, $this->validate_messages, $this->validate_attributes);

        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        }
        );

        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }
}
