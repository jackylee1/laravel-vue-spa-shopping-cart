<?php

namespace App\Http\Controllers\Api;

use App\Traits\ValidateTrait;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ValidateTrait;

    public function update(Request $request, $id)
    {
        $password_regex = 'regex:/^.*(?=.{3,})(?=.*[a-z,A-Z])(?=.*[0-9])(?=.*[\d\X]).*$/';
        $password_regex_message = 'Пароль имеет не верный формат. Пароль должен состоять как минимум с 3 символов латинского алфавита включа один символ в верхнем регистре и 3 цифр.';

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

        $this->validate_messages['new_password.regex'] = $password_regex_message;
        $this->validate_messages['new_password_confirmation.regex'] = $password_regex_message;

        $this->setValidateRule([
            'name' => 'required|string|max:191',
            'surname' => 'required|string|max:191',
            'patronymic' => 'required|string|max:191',
            'phone' => 'required|phone:UA|unique:users,phone,' . auth()->user()->id,
            'email' => 'required|email|unique:users,email,' . auth()->user()->id
        ]);

        $this->setValidateAttribute([
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'patronymic' => 'Отчество',
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
            'user_patronymic' => $request->patronymic,
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
}
