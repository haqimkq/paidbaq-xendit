<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DisbursementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return true;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "external_id" => "required|max:1000",       
            "bank_code" => "required|digits_between:7,17",       
            "account_holder_name" => "required",       
            "account_number" => "required",       
            "description" => "required",       
            "amount" => "required|numeric|digits_between:1,10",       
        ];
    }
}
