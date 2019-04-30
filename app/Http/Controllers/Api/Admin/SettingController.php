<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Setting;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    use ValidateTrait;

    private function generationValidation() {
        $this->setValidateRule([
            'phone1' => 'nullable|phone:UA|max:191',
            'phone2' => 'nullable|phone:UA|max:191',
            'email' => 'nullable|email',
            'index_m_title' => 'nullable|string|max:50000',
            'index_m_description' => 'nullable|string|max:50000',
            'index_m_keywords' => 'nullable|string|max:50000',
        ]);

        $this->setValidateAttribute([
            'phone1' => 'Контактный телефон (1)',
            'phone2' => 'Контактный телефон (2)',
            'email' => 'Контактный E-Mail',
        ]);
    }

    public function get()
    {
        return response()->json([
            'settings' => Setting::getItems()
        ]);
    }

    public function update(Request $request)
    {
        $this->generationValidation();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        Setting::updateModel('phone1', $request->phone1);
        Setting::updateModel('phone2', $request->phone2);
        Setting::updateModel('email', $request->email);
        Setting::updateModel('index_m_title', $request->index_m_title);
        Setting::updateModel('index_m_description', $request->index_m_description);
        Setting::updateModel('index_m_keywords', $request->index_m_keywords);

        return response()->json([
            'status' => 'success',
            'settings' => Setting::getItems(),
            'message' => 'Данные успешно обновлены'
        ]);
    }
}
