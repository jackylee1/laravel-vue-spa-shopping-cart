<?php

function arrayFlatten($items) {
    if (! is_array($items)) {
        return [$items];
    }

    return array_reduce($items, function ($carry, $item) {
        return array_merge($carry, arrayFlatten($item));
    }, []);
}