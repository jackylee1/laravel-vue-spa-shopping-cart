<?php

namespace App\Tools;

class DateTimeTools {
    /**
     * @param $datetime
     * @return object
     */
    public static function explodeRequestDateTime($datetime) {
        $datetime = explode('T', $datetime);

        return (object)[
            'date' => isset($datetime[0]) ? $datetime[0] : null,
            'time' => isset($datetime[1]) ? explode('.', $datetime[1])[0] : null
        ];
    }
}