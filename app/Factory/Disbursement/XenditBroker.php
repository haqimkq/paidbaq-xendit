<?php 
namespace App\Factory\Disbursement;

use App\Factory\Disbursement\Abstracts\BaseDisbursement;
use App\Factory\Disbursement\Contract\AdapterInterface;
use App\Factory\Disbursement\Contract\DisbursementInterface;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;

class XenditBroker extends Facade{

    const STORE_REQUEST_ERROR = "STORE_REQUEST_ERROR";
    const STORE_RESPONSE_ERROR = "STORE_RESPONSE_ERROR";
    const API_ERROR = "API_ERROR";
    
    
   
}