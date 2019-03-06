<?php

function getOnlyCharacters($str) {
    return mb_strtolower(preg_replace("/[^a-zA-Zа-яА-Я\d+]/u", '', $str));
}

function prepareForLike($str) {
    return preg_replace("/[ ]+/u", "%%", $str);
}

function cleanStr($str) {
    return trim(preg_replace('/\s\s+/', ' ', str_replace("\n", ' ', $str)));
}