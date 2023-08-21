<?php

namespace App\Factory\Disbursement\Exceptions;

use App\Factory\Disbursement\Contract\ExceptionInterface;
use App\Factory\Disbursement\XenditBroker;
use Exception;
use Illuminate\Support\Facades\Log;

class XenditServiceException extends Exception implements ExceptionInterface
{
  
    protected $errorCode;
    protected $message;
    protected $code;
    protected $dontReport = [];

    // CONST 

    public function __construct($message, $code, $errorCode)
    {
        if (!$message) {
            throw new $this('Unknown '. get_class($this));
        }

        parent::__construct($message, $code);
        $this->errorCode = $errorCode;
        // dd($this->errorCode);
        Log::error($message);
    }
    
    public function getProductionMessage()
    {
        if(XenditBroker::STORE_REQUEST_ERROR) 
        {
            return "Kesalahan saat menyimpan request";

        }else if(XenditBroker::STORE_RESPONSE_ERROR) {
            return "Kesalahan saat menyimpan request";
        } else {
            return "Kesalahan server";
        }
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
