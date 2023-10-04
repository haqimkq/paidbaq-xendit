<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\AccountCreatedNotificationJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Xendit\Xendit;
use App\Traits\ApiResponse;
use App\Models\Xenplatform;
use App\Models\Transfer;
use App\Models\Feerule;
use Illuminate\Support\Facades\Log;

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
            
            $response = \Xendit\Platform::createAccount($request->all());
            $xenplatform = new Xenplatform();
            $xenplatform->email = $request->email;
            $xenplatform->type = $request->type;
            $xenplatform->business_name = $request->public_profile["business_name"];
            $xenplatform->accountID = $response["id"];
            $xenplatform->save();
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
            $validator = Validator::make($request->all(), [
                "reference" => "required",
                "amount" => "required|numeric",
                "source_user_id" => "required",
                "destination_user_id" => "required",
            ]);
            if ($validator->fails()) {
                return response()->json("Validation Failed", 422);
            }
            $response = \Xendit\Platform::createTransfer($request->all());
            $transfer = new Transfer();
            $transfer->transfer_id = $response->transfer_id;
            $transfer->reference = $request->reference;
            $transfer->source_user_id = $request->source_user_id;
            $transfer->destination_user_id = $request->destination_user_id;
            $transfer->amount = $request->amount;
            $transfer->status = $response->status;
            $transfer->save();

            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }

    }

    public function feeRule(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "name" => "required",
                "description" => "required|numeric",
                "Feerule.*.unit" => "required",
                "Feerule.*.amount" => "required",
                "Feerule.*.currency" => "required",
            ]);
            if ($validator->fails()) {
                return response()->json("Validation Failed", 422);
            }

            $response = \Xendit\Platform::createFeeRule($request->all());

            $feerule = new Feerule();
            $feerule->feeruleId = $response->id;
            $feerule->name = $request->name;
            $feerule->description = $request->description;
            $feerule->routes = json_encode($request->routes);
            $feerule->metadata = json_encode($request->metadata);
            $feerule->save();

            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }

    public function getBalance(Request $request, $type){
        try {
            // balance types: CASH, TAX, HOLDING";
            $params = $request->only(["for-user-id"]);
            // $response = \Xendit\Disbursements::retrieve($id, $params);
            $response = \Xendit\Balance::getBalance($type, $params);
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }

    public function notification(Request $request)
    {
        Log::info("Receive account notification : ".json_encode($request->all()));
        try{
            if(count($request->all()) == 0){
                return $this->httpError("data not found");
            }
            $updateDisbursement  = Xenplatform::where('accountID', $request->data["id"])->update([
                "accountID"=>$request->data["id"],
                "email"=>$request->data["email"],
                "country" => $request->data["country"],
                "status" => $request->data["status"],
            ]);
            
            AccountCreatedNotificationJob::dispatch( $request->all())
                ->onQueue("xendisbursement");
            return $this->httpSuccess($request->all());
        } catch(\Exception $e){
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }

    public function suspensionNotification(Request $request)
    {
        try{
            if(count($request->all()) == 0){
                return $this->httpError("data not found");
            }
            $updateDisbursement  = Xenplatform::where('accountID', $request->data["id"])->update([
                "accountID"=>$request->data["id"],
                "email"=>$request->data["email"],
                "country" => $request->data["country"],
                "status" => $request->data["status"],
            ]);
            
            AccountCreatedNotificationJob::dispatch( $request->all())
                ->onQueue("xendisbursement");
            return $this->httpSuccess($request->all());
        } catch(\Exception $e){
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }


}
