<?php

function prettify($str) {
    $words = explode(' ', $str);
    foreach ($words as $i => $word) {
        $words[$i] = ucfirst(strtolower($word));
    }
    return implode(' ', $words);
}