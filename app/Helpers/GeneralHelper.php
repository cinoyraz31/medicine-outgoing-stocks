<?php

namespace App\Helpers;

class GeneralHelper
{
    public static function formatValidationError($array, $slug = "<br>") {
        $errorE = [];
        foreach($array as $key => $val){
            $errorE[] = $val[0];
        }
        $errorMessages = implode($slug, $errorE);
        return $errorMessages;
    }
}
