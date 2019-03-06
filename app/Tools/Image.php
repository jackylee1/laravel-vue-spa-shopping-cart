<?php

namespace App\Tools;

use Intervention\Image\ImageManagerStatic as ImageIntervention;

class Image
{
    private static $image,
        $path_info,
        $image_name,
        $params;

    /**
     * @return bool
     */
    private static function validation() {
        if (!array_key_exists('width', self::$params) || !array_key_exists('height', self::$params)) {
            return false;
        }

        return true;
    }

    /**
     * @param $image
     * @return \Intervention\Image\Image
     */
    private static function initImage($image) {
        return ImageIntervention::make(public_path("/app/$image"));
    }

    /**
     * @param $image
     */

    private static function pathInfo($image) {
        self::$path_info = pathinfo(storage_path($image));
    }

    private static function saveImage() {
        self::$image->save(public_path('/app/' . self::$params['path'] . self::$image_name), 100);
    }

    /**
     * @param $image
     * @param array $params
     * @return bool|object
     */
    public static function fit($image, array $params) {
        self::$params = $params;

        if (!self::validation()) {
            return false;
        }

        self::pathInfo($image);
        self::$image_name = uniqid() . '.' . self::$path_info['extension'];
        self::$image = self::initImage($image);
        self::$image->fit(self::$params['width'], self::$params['height'], function ($constraint) {
            $constraint->upsize();
        });
        self::saveImage();

        return (object)['file_name' => self::$image_name];
    }

    /**
     * @param $ini_path
     * @param $dest_path
     * @param array $params
     * @return mixed
     */
    public static function resize($ini_path, $dest_path, $params = []) {
        $width = !empty($params['width']) ? $params['width'] : null;
        $height = !empty($params['height']) ? $params['height'] : null;
        $constraint = !empty($params['constraint']) ? $params['constraint'] : false;
        $rgb = !empty($params['rgb']) ? $params['rgb'] : 0xFFFFFF;
        $quality = !empty($params['quality']) ? $params['quality'] : 100;
        $aspect_ratio = isset($params['aspect_ratio']) ? $params['aspect_ratio'] : true;
        $crop = isset($params['crop']) ? $params['crop'] : true;
        if (!file_exists($ini_path)) return false;
        if (!is_dir($dir = dirname($dest_path))) mkdir($dir);
        $img_info = getimagesize($ini_path);
        if ($img_info === false) return false;
        $ini_p = $img_info[0] / $img_info[1];
        if ($constraint) {
            $con_p = $constraint['width'] / $constraint['height'];
            $calc_p = $constraint['width'] / $img_info[0];
            if ($ini_p < $con_p) {
                $height = $constraint['height'];
                $width = $height * $ini_p;
            } else {
                $width = $constraint['width'];
                $height = $img_info[1] * $calc_p;
            }
        } else {
            if (!$width && $height) {
                $width = ($height * $img_info[0]) / $img_info[1];
            } else if (!$height && $width) {
                $height = ($width * $img_info[1]) / $img_info[0];
            } else if (!$height && !$width) {
                $width = $img_info[0];
                $height = $img_info[1];
            }
        }

        preg_match('/\.([^\.]+)$/i', basename($ini_path), $match);
        $ext = $match[1];

        $output_format = ($ext == 'jpg') ? 'jpeg' : $ext;
        $format = strtolower(substr($img_info['mime'], strpos($img_info['mime'], '/') + 1));

        $icfunc = "imagecreatefrom" . $format;
        $iresfunc = "image" . $output_format;

        if (!function_exists($icfunc)) return false;

        $dst_x = $dst_y = 0;
        $src_x = $src_y = 0;
        $res_p = $width / $height;
        if ($crop && !$constraint) {
            $dst_w = $width;
            $dst_h = $height;
            if ($ini_p > $res_p) {
                $src_h = $img_info[1];
                $src_w = $img_info[1] * $res_p;
                $src_x = ($img_info[0] >= $src_w) ? floor(($img_info[0] - $src_w) / 2) : $src_w;
            } else {
                $src_w = $img_info[0];
                $src_h = $img_info[0] / $res_p;
                $src_y = ($img_info[1] >= $src_h) ? floor(($img_info[1] - $src_h) / 2) : $src_h;
            }
        } else {
            if ($ini_p > $res_p) {
                $dst_w = $width;
                $dst_h = $aspect_ratio ? floor($dst_w / $img_info[0] * $img_info[1]) : $height;
                $dst_y = $aspect_ratio ? floor(($height - $dst_h) / 2) : 0;
            } else {
                $dst_h = $height;
                $dst_w = $aspect_ratio ? floor($dst_h / $img_info[1] * $img_info[0]) : $width;
                $dst_x = $aspect_ratio ? floor(($width - $dst_w) / 2) : 0;
            }
            $src_w = $img_info[0];
            $src_h = $img_info[1];
        }

        $isrc = $icfunc($ini_path);
        $idest = imagecreatetruecolor($width, $height);

        if (($format == 'png' || $format == 'gif') && $output_format == $format) {
            imagealphablending($idest, false);
            imagesavealpha($idest, true);
            imagefill($idest, 0, 0, IMG_COLOR_TRANSPARENT);
            imagealphablending($isrc, true);
            $quality = 0;
        } else {
            imagefill($idest, 0, 0, $rgb);
        }

        imagecopyresampled($idest, $isrc, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

        $res = $iresfunc($idest, $ini_path, $quality);

        imagedestroy($isrc);
        imagedestroy($idest);

        return $res;
    }
}