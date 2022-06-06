<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\VirtualAccountCreatedNotificationJob;
use App\Jobs\VirtualAccountNotificationJob;
use App\Models\VaPaymentNotification;
use App\Models\VirtualAccount;
use App\Services\PaibaqClient;
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
        $expiredAt = Carbon::now()->addHours(2)->format("c");
        $request->merge(["expiration_date" => $expiredAt]);
        try{

            $response = \Xendit\VirtualAccounts::update($id, $request->all());
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }

    }

    /**
     * Ketika telah terima pembayaran sistem akan memberi tahu
     * xendit bahwa PaidbaQ telah menerima pembayaran melalui funcsi ini
     * @param Illuminate\Http\Request $request
     * @param string $id
     */
    public function getVirtualAccount(Request $request, $id) 
    {
        
        try{

            $response = \Xendit\VirtualAccounts::retrieve($id);
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }

    }

    /**
     * Ketika telah terima pembayaran sistem akan memberi tahu
     * xendit bahwa PaidbaQ telah menerima pembayaran melalui fungsi ini
     * @param Illuminate\Http\Request $request
     * @param string $id
     */
    public function getVirtualAccountPayment(Request $request, $id) 
    {
        
        try{
            $response = \Xendit\VirtualAccounts::getFVAPayment($id);
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }

    }

    public function notification(Request $request)
    {  
        $trxTimeStamp = Carbon::parse($request->transaction_timestamp);
        $trxTimeStamp->setTimezone('Asia/Jakarta');
        $request->merge([
            "transaction_timestamp_formatted" => $trxTimeStamp->format("Y-m-d H:i:s"),
            "virtual_account_id" => $request->id
        ]);

        $params = $request->except(["id"]);

        try{
            # Store request to database
            VaPaymentNotification::create($params);

            # dispatching notification job to Backos 
            VirtualAccountNotificationJob::dispatch( $request->except(["virtual_account_id"]))
                ->onQueue("clientnotification");

            return $this->httpSuccess($request->all());
        } catch(\Exception $e){
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }

    public function createdNotification(Request $request)
    {
        try{

            Log::info("Created Callback ------- ".json_encode($request->all()));
            VirtualAccountCreatedNotificationJob::dispatch( $request->all())
                ->onQueue("clientnotification");

            return $this->httpSuccess($request->all());
        } catch(\Exception $e){
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }

    public function simulatePayment(Request $request, $extID)
    {
        $ch = curl_init();
        $data["amount"] = $request->amount;
        
        $payload = json_encode($data);
        $ch = curl_init("https://api.xendit.co/callback_virtual_accounts/external_id=".$extID."/simulate_payment");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, env("Xendit_api_key") . ":");  
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
         
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload))
        );
         
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        dd($info);

    }
    
}
