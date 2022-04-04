<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Xendit\Xendit;
use App\Traits\ApiResponse;

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
            $response = \Xendit\Platform::createAccount($request->all());
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }

    public function callback()
    {
        try {
            $callbackUrlParams = [
                'url' => $_ENV['Xendit_urlCallback_xenplatform']
            ];
            $callbackType = 'disbursement';
            $response = \Xendit\Platform::setCallbackUrl($callbackType, $callbackUrlParams);
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
            $response = \Xendit\Platform::createTransfer($request->all());
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }

    }

    public function feeRule(Request $request)
    {
        try {
            $response = \Xendit\Platform::createFeeRule($request->all());
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }

    /* public function createInvoiceSubAcc(Request $request)
    {
        try {
            $response = Xendit\Platform::createAccount($request->all());
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    } */

    public function getBalance($type){
        try {
            // balance types: CASH, TAX, HOLDING";
            $response = \Xendit\Balance::getBalance($type);
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }


}
