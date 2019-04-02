<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\LinkToSocialNetwork;
use App\Tools\File;
use App\Tools\Image;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LinkToSocialNetworkController extends Controller
{
    use ValidateTrait;

    private $path,
            $resize_params;

    public function __construct() {
        parent::__construct();

        $this->path = 'public/images/social_network/';
        $this->resize_params = [
            'width' => 83,
            'height' => 83,
            'aspect_ratio' => true,
            'crop' => true
        ];
    }

    private function validationId() {
        $this->setValidateRule([
            'id' => 'required|integer|exists:link_to_social_networks,id'
        ]);
    }

    private function validationGeneration($update = false) {
        if ($update) {
            $this->validationId();
        }

        $this->setValidateRule([
            'url' => 'nullable|url',
            'image' => ($update) ? 'nullable' : 'required' . '|image|max:2048',
            'sorting_order' => 'required|integer'
        ]);

        $this->setValidateAttribute([
            'url' => 'Ссылка',
            'image' => 'Изображение',
            'sorting_order' => 'Порядок сортировки'
        ]);
    }

    public function index()
    {
        return response()->json([
            'status' => 'success',
            'link_to_social_networks' => LinkToSocialNetwork::getLinks()
        ]);
    }

    public function store(Request $request)
    {
        $this->validationGeneration();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $image_origin = File::upload($request, ['file_key' => 'image', 'path_save' => $this->path]);
        $image_preview = File::upload($request, ['file_key' => 'image', 'path_save' => $this->path]);

        Image::resize(storage_path("app/$image_preview->full_path"), storage_path("app/$this->path"), $this->resize_params);

        $model = LinkToSocialNetwork::createModel($image_origin->file_name, $image_preview->file_name);

        return response()->json([
            'status' => 'success',
            'link_to_social_network' => $model,
            'image' => str_replace('public', '', $this->path.$image_preview->file_name),
            'message' => 'Ссылка успешно создана'
        ]);
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

        return response()->json([
            'status' => 'success',
            'link_to_social_network' => LinkToSocialNetwork::getLink($id)
        ]);
    }

    public function update(Request $request)
    {
        $this->validationGeneration(true);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $image_origin = $image_preview = null;

        if ($request->hasFile('image')) {
            $image_origin = File::upload($request, ['file_key' => 'image', 'path_save' => $this->path]);
            $image_preview = File::upload($request, ['file_key' => 'image', 'path_save' => $this->path]);

            Image::resize(public_path("app/$image_preview->full_path"), public_path("app/$this->path"), $this->resize_params);

            $image_origin = $image_origin->file_name;
            $image_preview = $image_preview->file_name;
        }

        $model = LinkToSocialNetwork::updateModel($image_origin, $image_preview);

        if ($model->image_preview !== null) {
            $image_preview = str_replace('public', '', $this->path.$model->image_preview);
        }

        return response()->json([
            'status' => 'success',
            'link_to_social_network' => $model,
            'image' => $image_preview,
            'message' => 'Данные ссылки были обновлены'
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

        LinkToSocialNetwork::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Ссылка была удалена'
        ]);
    }
}
