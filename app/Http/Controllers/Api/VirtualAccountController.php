<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VirtualAccount;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Xendit\Xendit;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class VirtualAccountController extends Controller
{
    use ApiResponse;
    public function __construct(){
        Xendit::setApiKey($_ENV['Xendit_api_key']);
    }

    public function create(Request $request) 
    {
        $request->only([
            "external_id", 
            "bank_code", 
            "name", 
            "is_closed", 
            "expected_amount", 
            "virtual_account_number", 
            "description", 
        ]);
        
       
        try {
            
            $validator = Validator::make($request->all(), [
                "external_id" => "required|max:1000",
                "bank_code" => "required",
                "name" => "required",
                "expected_amount" => "required|numeric",
                "virtual_account_number" => "required|numeric",
            ]);
            
            if ($validator->fails()) {
                
                $errors = $validator->errors();
                $message = implode(", ", $errors->all());
                return $this->httpUnprocessableEntity($message);
            }
            $expiredAt = Carbon::now()->addHours(2)->format("c");
            Log::info($expiredAt);
            $request->merge(["expiration_date" => $expiredAt]);
            $va = VirtualAccount::create($request->all());
            $response = \Xendit\VirtualAccounts::create($request->all());
            Log::info($response);
            if($response) {
                $va->update([
                    "transaction_id" => $response["id"],
                    "owner_id" => $response["owner_id"],
                    "account_number" => $response["account_number"],
                    "merchant_code" => $response["merchant_code"],
                    "is_single_use" => $response["is_single_use"],
                    "status" => $response["status"],
                ]);
            }
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }
    public function update(Request $request, $id) 
    {
        $request->only(["external_id", "bank_code", "name" , "is_closed" ,"expected_amount", "description"]);
        // print_r($request->all());
        try{

            $response = \Xendit\VirtualAccounts::update($id, $request->all());
            print_r($response);
            // die;
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }

    }

    public function getVirtualAccount(Request $request, $id) 
    {
        
        try{

            $response = \Xendit\VirtualAccounts::getFVAPayment($id);
            print_r($response);
            // die;
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }

    }

    public function notification(Request $request)
    {
        try{
            // if(count($request->all()) == 0){
            //     return $this->httpError("data not found");
            // }
            // $updateDisbursement  = Disbursement::where('external_id', $request->external_id)->update([
            //     "status"=>$request->status,
            //     "failure_code" => $request->failure_code,
            //     "is_instant" => $request->is_instant,
            // ]);
            return $this->httpSuccess($request->all());
        } catch(\Exception $e){
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }
}
