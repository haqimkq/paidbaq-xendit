<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ArrayHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Xendit\Xendit;
use App\Traits\ApiResponse;
use App\Models\Disbursement;

class DisbursementController extends Controller
{
    use ApiResponse;
    public function __construct(){
        Xendit::setApiKey($_ENV['Xendit_api_key']);
    }

    public function index($id)
    {
        try {
            $response = \Xendit\Disbursements::retrieve($id);
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }

    public function getByExternalID($externalId)
    {
        try {
            $response = \Xendit\Disbursements::retrieveExternal($externalId);
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }
    

    public function createAccount(Request $request)
    {
        $inputs = $request->only([
            "X-IDEMPOTENCY-KEY", 
            "for-user-id", 
            "external_id", 
            "amount", 
            "bank_code", 
            "account_holder_name", 
            "account_number", 
            "description"
        ]);
        $modelData = ArrayHelper::toDashKey( array_change_key_case($inputs) );
        
        try {
            
            $validator = Validator::make($request->all(), [
                "external_id" => "required|max:1000",
                "bank_code" => "required",
                "account_holder_name" => "required",
                "account_number" => "required",
                "description" => "required",
                "amount" => "required|numeric|digits_between:1,10",
            ]);

            if ($validator->fails()) {
                return response()->json("Validation Failed", 422);
            }

            Disbursement::create($modelData);
            $response = \Xendit\Disbursements::create($inputs);
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }

    public function notification(Request $request)
    {
        try{
            if(count($request->all()) == 0){
                return $this->httpError("data not found");
            }
            $updateDisbursement  = Disbursement::where('external_id', $request->external_id)->update([
                "status"=>$request->status,
                "failure_code" => $request->failure_code,
                "is_instant" => $request->is_instant,
            ]);
            return $this->httpSuccess($request->all());
        } catch(\Exception $e){
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }
}
