<?php

function getOnlyCharacters($str) {
    return mb_strtolower(preg_replace("/[^a-zA-Zа-яА-Я\d+]/u", '', $str));
}

function prepareForLike($str) {
    $str = preg_replace('/[^a-zA-Zа-яА-Я\d+]/ui', ' ', $str);

    return preg_replace("/[ ]+/u", "%%", $str);
}

function cleanStr($str) {
    return trim(preg_replace('/\s\s+/', ' ', str_replace("\n", ' ', $str)));
}

function removeExtraSpaces($str) {
    return preg_replace('/\s+/', ' ', trim($str));
}

function addSpaceBetweenNumbersAndLetters($str) {
    return preg_replace('/(?<=[a-z])(?=\d)|(?<=\d)(?=[a-z])/i', ' ', $str);
}

function clearAllSpaces($str) {
    return str_replace(' ', '', $str);
}

function getLikeData($str) {
    $str = removeExtraSpaces(mb_strtolower($str));

    return [
        'str' => $str,
        'add_spaces' => prepareForLike(addSpaceBetweenNumbersAndLetters($str)),
        'clear_spaces' => prepareForLike(clearAllSpaces($str)),
        'like' => prepareForLike($str),
    ];
}

function num2str($n, $text_forms) {
    $n = abs($n) % 100; $n1 = $n % 10;
    if ($n > 10 && $n < 20) { return $text_forms[2]; }
    if ($n1 > 1 && $n1 < 5) { return $text_forms[1]; }
    if ($n1 == 1) { return $text_forms[0]; }
    return $text_forms[2];
}