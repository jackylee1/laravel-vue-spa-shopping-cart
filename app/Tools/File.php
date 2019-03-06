<?php

namespace App\Tools;

use \Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File as FileFacade;

class File
{
    /**
     * @param $path
     * @param $files
     */
    public static function delete($path, $files) {
        if (is_string($files)) {
            Storage::delete($path . $files);
        }

        if (is_array($files) || is_a($files, 'Illuminate\Database\Eloquent\Collection')) {
            $files = (!is_a($files, 'Illuminate\Database\Eloquent\Collection')) ? collect($files) : $files;
            $files->each(function ($item) use ($path) {
                $full_path = $path . $item;
                if (Storage::exists($full_path)) {
                    Storage::delete($full_path);
                }
            });
        }
    }

    /**
     * @param $request
     * @param $params
     * @return bool|object
     */
    public static function upload($request, $params) {
        if (!array_key_exists('file_key', $params) || !array_key_exists('path_save', $params)) {
            return Log::error('No required parameters');
        }

        $params = (object)$params;

        if (!$request->hasFile($params->file_key)) {
            return Log::error('The data in the request is not a file.');
        }

        $prefix_name = (isset($params->prefix_name)) ? $params->prefix_name : '';
        $exp = $request->{$params->file_key}->getClientOriginalExtension();
        $file_name = uniqid($prefix_name) . '.' . $exp;

        if (!FileFacade::exists(storage_path($params->path_save))) {
            Storage::makeDirectory($params->path_save);
        }

        $request->{$params->file_key}->storeAs($params->path_save, $file_name);

        return (object)[
            'path_save' => $params->path_save,
            'full_path' => "{$params->path_save}{$file_name}",
            'file_name' => $file_name,
            'exp' => $exp
        ];
    }

    /**
     * @param $url
     * @param $path
     * @param $name
     * @return bool
     */
    public static function saveByUrl($url, $path, $name) {
        $contents = file_get_contents($url);
        if (!FileFacade::exists(storage_path($path))) {
            Storage::makeDirectory($path);
        }
        Storage::put($path . $name, $contents);
        return true;
    }
}