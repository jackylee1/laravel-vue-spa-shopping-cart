<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Subscribe;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SubscribeController extends Controller
{
    use ValidateTrait;

    private function validationId() {
        $this->setValidateRule([
            'id' => 'required|integer|exists:subscribes,id'
        ]);
    }

    private function generationValidation($update = false) {
        $email_rules = 'required|email';
        if ($update) {
            $this->validationId();

            $this->setValidateRule([
                'email' => "$email_rules|unique:subscribes,email,".\request()->get('id')
            ]);
        }
        else {
            $this->setValidateRule([
                'email' => "$email_rules|unique:subscribes,email"
            ]);
        }
        $this->setValidateAttribute([
            'email' => 'E-Mail'
        ]);
    }

    public function index()
    {
        return response()->json([
            'subscribes' => Subscribe::getSubscribes()
        ]);
    }

    public function store(Request $request)
    {
        $this->generationValidation();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $subscribe = Subscribe::createModel();
        $subscribe->read_status = true;
        $subscribe->save();

        return response()->json([
            'status' => 'success',
            'subscribe' => $subscribe,
            'message' => 'Запись успешно создано',
        ]);
    }

    private function checkUpdateReadStatus($subscribe) {
        $deduct_from_notifications = false;

        if (!$subscribe->read_status) {
            $subscribe->read_status = true;
            $subscribe->save();

            $deduct_from_notifications = true;
        }

        return $deduct_from_notifications;
    }

    public function show($id)
    {
        $this->validationId();
        $validator = Validator::make(['id' => $id], $this->validate_rules);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $subscribe = Subscribe::getSubscribe($id);

        return response()->json([
            'subscribe' => $subscribe,
            'deduct_from_notifications' => $this->checkUpdateReadStatus($subscribe)
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->generationValidation(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $subscribe = Subscribe::updateModel($id);

        return response()->json([
            'status' => 'success',
            'subscribe' => $subscribe,
            'message' => 'Запись успешно обновлена'
        ]);
    }

    public function updateReadStatus(Request $request) {
        $this->validationId();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $subscribe = Subscribe::getSubscribe($request->id);
        $subscribe->read_status = true;
        $subscribe->save();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function destroy($id)
    {
        $this->validationId();
        $validator = Validator::make(['id' => $id], $this->validate_rules);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        Subscribe::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Запись была удалена'
        ]);
    }
}
