<?php

namespace App\Helpers;
use Illuminate\Support\Facades\DB;
class ArrayHelper {
	public static function toDashKey($array) {
        $replacedKeys = str_replace('-', '_', array_keys($array));
        return array_combine($replacedKeys, $array);
    }
}
