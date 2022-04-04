<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Xendit\Xendit;
use App\Traits\ApiResponse;

class TransactionController extends Controller
{
    use ApiResponse;
    public function __construct(){
        Xendit::setApiKey($_ENV['Xendit_api_key']);
    }
    public function index()
    {
        try {
            $param = [
                'types' => 'DISBURSEMENT',
                'query-param'=> 'true'
            ];
            $response = \Xendit\Transaction::list($param);
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }

    public function Detail($transaction_id)
    {
        try {
            $response = \Xendit\Transaction::detail($transaction_id);
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }
}
