<?php 
namespace App\Factory\Disbursement\Abstracts;

use App\Factory\Disbursement\Contract\AdapterInterface;
use App\Factory\Disbursement\Contract\DisbursementInterface;
use App\Http\Requests\BatchDisbursementStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Xendit\Disbursements;
use Xendit\Exceptions\ApiException;
use Xendit\Xendit;

abstract class BaseDisbursement {

    
    protected $response;
    protected $correlationId = null;
    abstract public function resolve(AdapterInterface $adapter) : DisbursementInterface;


    public function make(string $className, BatchDisbursementStoreRequest $request)
    {
    
        Xendit::setApiKey(env('Xendit_api_key'));
        $adapter = new $className($request);
        return $this->resolve($adapter);
    }

    public function getCorrelationId()
    {
        return $this->correlationId;
    }
    
    public function createBatchRequest(AdapterInterface $adapter,  array $payload)
    {
        $this->createLogRequest($adapter);
        try{

            $response = Disbursements::createBatch($payload);
            return $response;
        } catch(\Xendit\Exceptions\ApiException $e) {
            $adapter->handleApiError($e);
        }
       
    }

    private function createLogRequest($adapter)
    {
        try{
            $header = $adapter->getRequestHeader();
            $body = $adapter->getRequestBody();
            Log::debug($adapter->getCorrelationId() . " preparing request with body ". serialize($body) . " and headers ". serialize($header ) );
        
        } catch(\Exception $e) {

        }

    }


    
    public function createSingleRequest()
    {
        
    }
    
}