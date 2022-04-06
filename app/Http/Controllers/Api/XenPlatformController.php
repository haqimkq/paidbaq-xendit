<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Xendit\Xendit;
use App\Traits\ApiResponse;
use App\Models\Xenplatform;
// use App\Http\Requests\XenplatformRequest;
// use App\Http\Requests\TransferRequest;
// use App\Http\Requests\FeeruleRequest;

class XenPlatformController extends Controller
{
    use ApiResponse;
    public function __construct(){
        Xendit::setApiKey($_ENV['Xendit_api_key']);
    }
    public function index($account_id)
    {
        try {
            $response = \Xendit\Platform::getAccount($account_id);
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }

    public function createAccount(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "email" => "required|email|unique:xenplatforms",
                "type" => "required",
            ]);
            if ($validator->fails()) {
                return response()->json("Validation Failed", 422);
            }
            // print_r($request->business_profile["business_name"]);die;
            // $xenplatform = Xenplatform::create($request->all());
            $xenplatform = new Xenplatform();
            $xenplatform->email = $request->email;
            $xenplatform->type = $request->type;
            $xenplatform->business_name = $request->public_profile["business_name"];
            $xenplatform->save();

            $response = \Xendit\Platform::createAccount($request->all());
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }

    public function updateAccount(Request $request, $account_id)
    {
        try {
            $response = \Xendit\Platform::updateAccount($account_id,$request->all());
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }

    public function transfer(Request $request)
    {
        try {
            $response = \Xendit\Platform::createTransfer($request->all());
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }

    }

    public function feeRule(Request $request)
    {
        try {
            $response = \Xendit\Platform::createFeeRule($request->all());
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }

    public function getBalance($type){
        try {
            // balance types: CASH, TAX, HOLDING";
            $response = \Xendit\Balance::getBalance($type);
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }

    public function notification(Request $request)
    {
        try{
        $updateDisbursement  = Xenplatform::where('email', $request->data["email"])->update([
            "id"=>$request->data["id"], 
            "country" => $request->data["country"],
            "status" => $request->data["status"],
        ]);
        return $this->httpSuccess($request);
        } catch(\Exception $e){
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }


}
