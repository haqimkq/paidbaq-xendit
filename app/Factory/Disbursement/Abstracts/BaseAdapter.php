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

abstract class BaseAdapter {

    
    protected $correlationId = null;
    protected $request;
    public function getCorrelationId()
    {
        return $this->correlationId;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setCorrelationId($id)
    {
        $this->correlationId = $id;
    }
    
    
}