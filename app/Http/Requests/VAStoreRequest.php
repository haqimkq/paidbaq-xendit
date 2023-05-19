<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
class VAStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $validator = [
            "external_id" => [ "required"],
            "bank_code" => [ "required"],
            "name" => [ "required"],
            "expected_amount" => [ "required"],
            "virtual_account_number" => [ "required"],
        ];

       
        return $validator;
    }

    public function failedValidation(Validator $validator) { 

        $errors = $validator->errors();
        $errorMessages = $errors->all();
        $validatorErrors = $validator->failed();
        $firstError = array_key_first($validatorErrors);
        $errorType = array_key_first($validatorErrors[$firstError]);
        $message = implode(", ", $errors->all());
        Log::info($validator->errors());
        throw new HttpResponseException(response()->json([
            "erorr_code" => "422",
            "error_message" => $message,
            "details" => $validator->errors(),
            
        ], 422)); 
    }
}
