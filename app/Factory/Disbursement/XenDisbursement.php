<?php 
namespace App\Factory\Disbursement;

use App\Factory\Disbursement\Abstracts\BaseDisbursement;
use App\Factory\Disbursement\Contract\AdapterInterface;
use App\Factory\Disbursement\Contract\DisbursementInterface;
use Illuminate\Support\Facades\Log;

class XenDisbursement extends BaseDisbursement implements DisbursementInterface{

    private $adapter;
    public function resolve(AdapterInterface $adapter): DisbursementInterface
    {
        $this->adapter = $adapter;
        $this->adapter->storeRequest();
        $this->response = $this->createBatchRequest($this->adapter, $this->adapter->getRequest() );
        $this->saveResponse();
        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function saveResponse()
    {
        return $this->adapter->storeResponse( 
            $this->response 
        );
    }
   
}