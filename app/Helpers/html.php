<?php

/**
 * @param $path
 * @param string $rel
 * @param string $type
 * @return string
 */
function style_ts($path, $rel = 'stylesheet', $type = 'text/css') {
    try {
        $ts = '?v=' . File::lastModified(public_path() . $path);
    } catch (Exception $e) {
        $ts = '';
    }
    return '<link rel="' . $rel . '" type="' . $type .'" href="' . $path . $ts . '">';
}
function script_ts($path, $public = true) {
    try {
        if ($public) {
            $path = public_path() . $path;
        }

        $ts = '?v=' . File::lastModified($path);
    } catch (Exception $e) {
        $ts = '';
    }
    return '<script src="' . $path . $ts . '"></script>';
}
/**
 * @param $path
 * @return string
 * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
 */
function imageBase64($path) {
    $path = public_path($path);
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = \File::get($path);
    if ($type == "svg") {
        return "data:image/svg+xml;base64," . base64_encode($data);
    } else {
        return "data:image/" . $type . ";base64," . base64_encode($data);
    }
}