<?php

namespace App\Helpers;
use Illuminate\Support\Facades\DB;
class AutogenHelper {

	/*public static function generateTransactionId($model, $field, $prefix, $diff=4){
		try {
			$initial = 100;
			$prefixLength = strlen(trim($prefix));
			$start = $prefixLength+1;
			$data = $model->select(DB::raw("max(SUBSTR(".$field.", ".$start.")) as latest"))->first();
			$latest = $initial;
			if($data && $data->latest !=0){
				$latest = (int) $data->latest;
			}
			// print_r($latest);
			// die();
			$next = $latest+1;
			$result = $prefix;
			$result .= str_pad($next, $diff, '0', STR_PAD_LEFT);
			return $result;
		} catch (\Exception $e) {
			print_r($e->getMessage());
			throw new \Exception($e->getMessage(), 1);

		}
	}*/

	public static function romawi($number) {
		$result = "";
	    if ($number < 1 || $number > 5000) {
	        $result = "Batas number 1 s/d 5000";
	    } else {
	        while ($number >= 1000) {
	            $result .= "M";
	            $number -= 1000;
	        }
	    }


	    if ($number >= 500) {
	        if ($number > 500) {
	            if ($number >= 900) {
	                $result .= "CM";
	                $number -= 900;
	            } else {
	                $result .= "D";
	                $number-=500;
	            }
	        }
	    }
	    while ($number>=100) {
	        if ($number>=400) {
	            $result .= "CD";
	            $number -= 400;
	        } else {
	            $number -= 100;
	        }
	    }
	    if ($number>=50) {
	        if ($number>=90) {
	            $result .= "XC";
	            $number -= 90;
	        } else {
	            $result .= "L";
	            $number-=50;
	        }
	    }
	    while ($number >= 10) {
	        if ($number >= 40) {
	            $result .= "XL";
	            $number -= 40;
	        } else {
	            $result .= "X";
	            $number -= 10;
	        }
	    }
	    if ($number >= 5) {
	        if ($number == 9) {
	            $result .= "IX";
	            $number-=9;
	        } else {
	            $result .= "V";
	            $number -= 5;
	        }
	    }
	    while ($number >= 1) {
	        if ($number == 4) {
	            $result .= "IV";
	            $number -= 4;
	        } else {
	            $result .= "I";
	            $number -= 1;
	        }
	    }

	    return ($result);
	}

	public static function randomNumber($lengh) {
		$characters = '0123456789';
		$randomString = '';

		for ($i = 0; $i < $lengh; $i++) {
		        $index = rand(0, strlen($characters) - 1);
		        $randomString .= $characters[$index];
		}

		return $randomString;
	}
}
