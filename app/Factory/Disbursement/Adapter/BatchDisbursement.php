<?php 
namespace App\Factory\Disbursement\Adapter;

use App\Factory\Disbursement\Abstracts\BaseAdapter;
use App\Factory\Disbursement\Contract\AdapterInterface;
use App\Factory\Disbursement\Exceptions\XenditServiceException;
use App\Factory\Disbursement\XenditBroker;
use App\Http\Requests\BatchDisbursementStoreRequest;
use App\Models\BatchDisbursement as ModelsBatchDisbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Xendit\Exceptions\ApiException;

    // "for-user-id" : "6299a7ba8378fa4f2341f7c1",
class BatchDisbursement extends BaseAdapter implements AdapterInterface{

   
    private $disbursementModel;
    
    public function __construct(BatchDisbursementStoreRequest $request)
    {
        $this->request = $request->only("for-user-id", "reference", "disbursements", "X-IDEMPOTENCY-KEY");
       
    }

    
    public function storeRequest()
    {
        DB::beginTransaction();
        $reference = Arr::get($this->request, "reference", null);
        $this->setCorrelationId($reference);

        try{
            $this->storeBatchDisbursementModel($reference);
            $this->storeBatchDisbursementItem( $this->transformData() );
            DB::commit();
        } catch(\Exception $e){
            DB::rollBack();
            throw new XenditServiceException($this->correlationId. " " .$e->getMessage() . " on line ".  $e->getLine() ." ".__CLASS__  , 500, XenditBroker::STORE_REQUEST_ERROR);
        }

        return $this;
    }

    private function storeBatchDisbursementModel($reference)
    {
        $this->disbursementModel = ModelsBatchDisbursement::create([
            "x_idempotency_key" => Arr::get($this->request, "X-IDEMPOTENCY-KEY"),
            "for_user_id" => Arr::get($this->request, "for-user-id"),
            "reference" => $reference,
        ]);
    }

    
    private function storeBatchDisbursementItem($payload)
    {
        $this->disbursementModel->data()->createMany($payload);
    }


    private function transformData()
    {
        $disbursements = Arr::get($this->request, "disbursements");
        return array_map(function($item) {
            return [
                "amount" => Arr::get($item, "amount"), 
                "bank_code" => Arr::get($item, "bank_code"), 
                "bank_account_name" => Arr::get($item, "bank_account_name"), 
                "bank_account_number" => Arr::get($item, "bank_account_number"), 
                "description" => Arr::get($item, "description"), 
                "external_id" => Arr::get($item, "external_id")
            ];
        },$disbursements);
    }

    public function storeResponse($response)
    {
        DB::beginTransaction();
        try{
            $this->disbursementModel->update(["request_status" => "SUCCESS"]);
            $this->disbursementModel
                ->response()
                ->create($response);
            DB::commit();
        } catch(\Exception $e) {
            throw new XenditServiceException($this->correlationId . " " . $e->getMessage() . " ON Line ".__CLASS__  , 500, XenditBroker::STORE_RESPONSE_ERROR);
        }
    }

    

    public function getRequestHeader()
    {
        $header["for-user-id"] = Arr::get($this->request , "for-user-id", null); 
        $header["X-IDEMPOTENCY-KEY"] = Arr::get($this->request , "X-IDEMPOTENCY-KEY", null); 
        return $header;
    }
    
    
    public function getRequestBody()
    {
        $body["reference"] = Arr::get($this->request , "reference", null); 
        $body["disbursements"] = Arr::get($this->request , "disbursements", []); 
        return $body;
    }

    public function handleApiError(ApiException $exception)
    {

        $this->disbursementModel->update(["request_status" => "FAILED"]);
        Log::error($this->getCorrelationId() . " " . $exception->getMessage() . " ON Line ".__CLASS__ ." ". XenditBroker::API_ERROR);
        throw new ApiException(
            $exception->getMessage(), 
            $exception->getCode(), 
            $exception->getErrorCode()
        );
    }


}