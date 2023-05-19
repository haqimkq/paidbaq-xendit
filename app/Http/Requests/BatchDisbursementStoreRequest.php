<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BatchDisbursementStoreRequest extends FormRequest
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
        return [
            // "reference" => "required|max:64",
            "for-user-id" => "max:64",
            "disbursements.*.external_id" => "required",
            "disbursements.*.amount" => "digits_between:4,18",
            "disbursements.*.bank_code" => "required",
            "disbursements.*.bank_account_name" => "required",
            "disbursements.*.description" => "required",
        ];
    }
}
