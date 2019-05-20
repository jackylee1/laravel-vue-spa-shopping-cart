<?php

namespace App\Tools;

class KeyTool
{
    /**
     * @param $key
     * @return mixed
     */
    private static function getKey($key) {
        return request()->get($key);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public static function getKeyUser($key) {
        $key = self::getKey($key);
        if ($key == null) {
            return md5(uniqid(rand(), true));
        }

        return $key;
    }
}