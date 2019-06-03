<?php

namespace App\Http\Controllers\Api\Admin;

use App\Notifications\Admin\SendPromotionalCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ValidateTrait;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Notifications\Notifiable;

class UserController extends Controller
{
    use ValidateTrait;
    use Notifiable;

    private function validateForUpdate() {
        $this->setValidateRule([
            'id' => 'required|integer|exists:users',
            'password' => 'nullable|min:6|regex:/^.*(?=.{3,})(?=.*[a-z,A-Z])(?=.*[0-9])(?=.*[\d\X]).*$/|confirmed',
        ]);
    }
    private function generateValidate($update = false) {
        if ($update) {
            $this->validateForUpdate();

            $this->setValidateRule([
                'email' => 'required|email|unique:users,email,'.request()->get('id')
            ]);
        }
        else {
            $this->setValidateRule([
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-z,A-Z])(?=.*[0-9])(?=.*[\d\X]).*$/|confirmed',
                'password_confirmation' => 'required'
            ]);
        }
        $this->setValidateRule([
            'user_name' => 'required|string|max:191',
            'user_surname' => 'required|string|max:191',
            'phone' => 'required|string|max:191',
            'status' => 'required|string|in:user,administration',
            'description' => 'nullable|string|max:2500',
            'reliability' => 'required|integer|in:0,1',
            'group_id' => 'nullable|integer|exists:user_groups,id',
            'discount' => 'nullable|integer|between:0,100'
        ]);
        $this->setValidateAttribute([
            'user_name' => 'Имя',
            'user_surname' => 'Фамилия',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'password' => 'Пароль',
            'password_confirm' => 'Повторите пароль',
            'status' => 'Статус пользователя',
            'description' => 'Примечание',
            'reliability' => 'Статус надежности',
            'group_id' => 'Группа пользователей',
            'discount' => 'Персональный процент скидки'
        ]);
    }

    public function index(Request $request)
    {
        $this->setValidateRule([
            'q' => 'nullable|string|max:191',
            'status' => 'nullable|in:all,user,administration'
        ]);
        $this->setValidateAttribute([
            'q' => 'Текст запроса',
            'status' => 'Статус пользователя'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $users = User::getUsers();

        return response()->json([
            'users' => $users
        ]);
    }

    public function show($id) {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:users'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        return response()->json([
            'user' => User::with(['group'])->find($id)
        ]);
    }

    public function store(Request $request)
    {
        $this->generateValidate();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $user = User::createModel();

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'message' => 'Пользователь успешно создан'
        ]);
    }


    public function update(Request $request, $id)
    {
        $this->generateValidate(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $user = User::updateModel();

        return response()->json([
            'status' => 'success',
            'message' => 'Данные о пользователе успешно изменены',
            'user' => $user
        ]);
    }

    public function destroy($id)
    {

        $user = User::find($id);
        $validator = Validator::make(['id' => $id], [
            'id' => [
                'required',
                'integer',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ($value === auth('api')->user()->id) {
                        return $fail('Вы не можете удалить учетную свою учетную запись');
                    }
                },
                function ($attribute, $value, $fail) use ($user) {
                    if ($user->status === 'administration') {
                        return $fail('Вы не можете удалить Администратора. Измените его статус, после удалите');
                    }
                }
            ]
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        User::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Пользователь успешно удален',
        ]);
    }

    public function handlePromotionalCode(Request $request) {
        $this->setValidateRule([
            'user_id' => 'required|integer|exists:users,id',
            'promotional_code_id' => 'required|integer|exists:promotional_codes,id'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $handle = User::handlePromotionalCode();
        $message = 'Промо-код был удален с пользователя';
        if ($handle['operation'] === 'add') {
            $handle['model']->send_status_to_email = 0;
            $message = 'Промо-код был добавлен к пользователю';
        }

        return response()->json([
            'status' => 'success',
            'operation' => $handle['operation'],
            'create_model' => $handle['model'],
            'message' => $message
        ]);
    }

    public function sendPromotionalCode(Request $request) {
        $this->setValidateRule([
            'user_id' => 'required|integer|exists:users,id',
            'promotional_code_id' => 'required|integer|exists:promotional_codes,id',
        ]);

        $user = User::havePromotionalCode();
        if ($user === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Этот Промо-код не принадлежит данному пользователю'
            ], 403);
        }

        $promotional_code = $user->promotionalCodes->firstWhere('promotional_code_id', $request->promotional_code_id);
        $user->notify(new SendPromotionalCode($user->name, $promotional_code));

        $promotional_code->send_status_to_email = 1;
        $promotional_code->save();

        return response()->json([
            'status' => 'success',
            'message' => "Промо-код отправлен пользователю: {$user->email} на E-mail: {$user->email}"
        ]);
    }
}
