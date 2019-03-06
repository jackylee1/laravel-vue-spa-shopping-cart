<?php

namespace App\Traits;

trait ValidateTrait
{
    private function insertInProperty($property, $data) {
        $keys = array_keys($data);
        $i = 0; $c = count($keys);
        while ($i < $c) {
            if (isset($this->{'validate_'.$property})) {
                $this->{'validate_'.$property}[$keys[$i]] = $data[$keys[$i]];
            }
            $i++;
        }
    }

    protected function setValidateRule($data) {
        if (is_array($data)) {
            $this->insertInProperty('rules', $data);
        }
    }

    protected function setValidateAttribute($data) {
        if (is_array($data)) {
            $this->insertInProperty('attributes', $data);
        }
    }
}