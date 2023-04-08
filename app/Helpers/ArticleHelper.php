<?php

namespace App\Helpers;

class ArticleHelper
{
    public static function splitString($str) {
        $array = [];

        if ($str) {
            $array = explode(',', trim($str));
        }

        return $array;
    }

}
