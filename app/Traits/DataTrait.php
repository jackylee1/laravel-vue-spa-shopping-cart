<?php

namespace App\Traits;

trait DataTrait {
    /**
     * @param $key
     * @param $value
     */
    protected function setData($key, $value = null) {
        if (is_array($key)) {
            collect($key)->each(function ($item) {
                if(isset($item[0])) {
                    $this->data[$item[0]] = (isset($item[1])) ? $item[1] : null;
                }
            });
        }
        elseif (is_string($key)) {
            $this->data[$key] = $value;
        }
    }

    /**
     * @param $key
     * @return mixed
     */
    protected function getData($key = null) {
        return ($key == null) ? $this->data : $this->data[$key];
    }

    /**
     * @param $errors
     */
    protected function setErrors($errors) {
        $this->setData('errors', $errors);
    }
}