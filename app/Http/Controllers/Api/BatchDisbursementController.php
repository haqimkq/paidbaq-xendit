<?php

namespace App\Http\Controllers\Api;

use App\Factory\Disbursement\Adapter\BatchDisbursement;
use App\Factory\Disbursement\Exceptions\XenditServiceException;
use App\Factory\Disbursement\XenDisbursement;
use App\Factory\Model\BatchUpdate;
use App\Http\Controllers\Controller;
use App\Http\Requests\BatchDisbursementStoreRequest;
use App\Models\BatchDisbursementCallback;
use App\Models\BatchDisbursementCallbackData;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Xendit\Disbursements;
use Xendit\Exceptions\ApiException;
use Xendit\Xendit;

class BatchDisbursementController extends Controller
{
    
    use ApiResponse;
    public function create(BatchDisbursementStoreRequest $request)
    {
        
        try{
            $xenDistburse = new XenDisbursement();
            $xenDistburse->make(BatchDisbursement::class, $request);
            return $this->httpSuccess($xenDistburse->getResponse());
        } catch(XenditServiceException $e){
            return $this->httpError( $e->getProductionMessage(), $e->getErrorCode(), $e->getCode() );
        } catch(ApiException $e) {
            return $this->httpError( $e->getMessage(), $e->getErrorCode(), $e->getCode() );
        } catch(\Exception $e) {
            return $this->httpError($e->getMessage());
        }
       
    }

    public function notification(Request $request)
    {
        DB::beginTransaction();
        try {
            $params = [
                "id" => $request->id,
                "created" => $request->created,
                "updated" => $request->updated,
                "reference" => $request->reference,
                "user_id" => $request->user_id,
                "total_uploaded_count" => $request->total_uploaded_count,
                "total_uploaded_amount" => $request->total_uploaded_amount,
                "approved_at" => $request->approved_at,
                "approver_id" => $request->approver_id,
                "status" => $request->status,
                "total_disbursed_count" => $request->total_disbursed_count,
                "total_disbursed_amount" => $request->total_disbursed_amount,
                "total_error_count" => $request->total_error_count,
                "total_error_amount" => $request->total_error_amount,
            ];
            $disbursement = $request->disbursements ?? [];
            $callbackModel = BatchDisbursementCallback::updateOrCreate(["id" => $request->id], $params);
            $callbackModel->disbursement()->update(["status" => $request->status]);
            $disbursement = array_map(function($item) use($disbursement, $callbackModel) {
                return [
                    "id" => Arr::get($item, "id", null),
                    "batch_disbursement_callback_id" =>$callbackModel->id,
                    "created" => Arr::get($item, "created", null),
                    "updated" => Arr::get($item, "updated", null),
                    "external_id" => Arr::get($item, "external_id", null),
                    "amount" => Arr::get($item, "amount", 0),
                    "bank_code" => Arr::get($item, "bank_code", null),
                    "bank_account_number" => Arr::get($item, "bank_account_number", 0),
                    "bank_account_name" => Arr::get($item, "bank_account_name", null),
                    "description" => Arr::get($item, "description", null),
                    "email_to" => Arr::get($item, "email_to", null),
                    "status" => Arr::get($item, "status", null),
                    "valid_name" => Arr::get($item, "valid_name", null),
                    "bank_reference" => Arr::get($item, "bank_reference", null),
                    "created_at" => date("Y-m-d H:i:s")
                ];
            }, $disbursement);
            $disbursementCallbackDataId = array_column($disbursement, "id");
            $callbackItemExist = BatchDisbursementCallbackData::whereIn("id", $disbursementCallbackDataId)->get()->toArray();
            $callbackItemExistId = array_column($callbackItemExist, "id");
            $disbursementChunked = array_chunk($disbursement, 200);
            $disbursementUpdate = [];
            foreach($disbursementChunked as $itemEx) {
                foreach($itemEx as $idx => $itemDsb) {
                    $itemId = Arr::get($itemDsb, "id", null);
                    if(in_array($itemId, $callbackItemExistId)) {
                        $disbursementUpdate[] = $itemDsb;
                        unset($disbursement[$idx]);
                    }
                     
                }
            }
            
            if($disbursementUpdate) {
                (new BatchUpdate())
                    ->chunk(1)
                    ->model( 
                        new BatchDisbursementCallbackData(),  
                        $disbursementUpdate ,
                        ["id", "batch_disbursement_callback_id"]
                    );
            }
            if($disbursement) BatchDisbursementCallbackData::insert($disbursement);
            DB::commit();
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return response()->json(["error_code" => "500", "error_message" => "terjadi kesalahan"], 500);
        }
        Log::debug(serialize($request->all()));
    }
}
