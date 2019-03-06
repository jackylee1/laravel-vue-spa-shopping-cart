<?php

namespace App\Http\Controllers\Api\Admin;

use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadFileController extends Controller
{
    use ValidateTrait;

    public function image(Request $request) {
        $this->setValidateRule([
            'image' => 'required|mimes:jpeg,jpg,png,gif',
        ]);
        $this->setValidateAttribute([
            'image' => 'Изображение'
        ]);

        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $ext = $request->image->getClientOriginalExtension();
        $name = str_replace(".$ext", '', $request->image->getClientOriginalName());
        $save_name = uniqid("{$name}_");
        $request->image->move(public_path('/upload/images'), "{$save_name}.{$ext}");


        return response()->json([
            'link' => url("/upload/images/{$save_name}.{$ext}"),
        ]);
    }
}
