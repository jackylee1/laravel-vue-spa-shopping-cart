<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\UserGroup;
use App\Models\UserInGroup;
use App\Traits\ValidateTrait;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserGroupController extends Controller
{
    use ValidateTrait;

    private function validateForUpdate() {
        $this->setValidateRule([
            'id' => 'required|integer|exists:user_groups'
        ]);
    }

    private function validateId($id) {
        $this->validateForUpdate();
        $validator = Validator::make(['id' => $id], $this->validate_rules);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
    }

    private function generateValidate($update = false) {
        if ($update) {
            $this->validateForUpdate();
        }
        $this->setValidateRule([
            'name' => 'required|string|max:255',
            'discount' => 'required|integer|min:0|max:100'
        ]);
        $this->setValidateAttribute([
            'name' => 'Наименование',
            'discount' => 'Скидна для группы'
        ]);
    }

    public function index()
    {
        return response()->json([
            'user_groups' => UserGroup::userGroups()
        ]);
    }

    public function store(Request $request)
    {
        $this->generateValidate();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $type = UserGroup::createModel();

        return response()->json([
            'status' => 'success',
            'user_group' => $type,
            'message' => 'Группа пользователей успешно создана'
        ]);
    }

    public function show($id)
    {
        $this->validateId($id);
        $user_group = UserGroup::userGroup($id);

        return response()->json([
            'status' => 'success',
            'user_group' => $user_group,
            'users_in_group' => UserInGroup::getUsers($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->generateValidate(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $type = UserGroup::updateModel();

        return response()->json([
            'status' => 'success',
            'user_group' => $type,
            'message' => 'Данные о греппе пользователей успешно обновлены'
        ]);
    }

    public function destroy($id)
    {
        $this->validateId($id);

        UserGroup::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Группа пользователей успешно удален'
        ]);
    }

    public function userActionHandler(Request $request) {
        $this->setValidateRule([
            'user_id' => 'required|integer|exists:users,id',
            'user_group_id' => 'required|integer|exists:user_groups,id'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $result = UserGroup::userActionHandler();

        $user = User::getUser($request->user_id);
        $message = "Пользователь {$user->name} [{$user->id}] был удален с группы \"{$result['user_group']->name}\"";
        if ($result['operation'] === 'added') {
            $message = "Пользователь {$user->name} [{$user->id}] был добавлен в группу \"{$result['user_group']->name}\"";
        }

        return response()->json([
            'operation' => $result['operation'],
            'status' => 'success',
            'message' => $message,
            'user' => $user
        ]);
    }
}
