<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Xendit\Xendit;
use App\Traits\ApiResponse;

class DisbursementController extends Controller
{
    use ApiResponse;
    public function __construct(){
        Xendit::setApiKey($_ENV['Xendit_api_key']);
    }

    public function index($id)
    {
        try {
            $response = \Xendit\Disbursements::retrieve($id);
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }

    public function getByExternalID($externalId)
    {
        try {
            $response = \Xendit\Disbursements::retrieveExternal($externalId);
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }

    public function createAccount(Request $request)
    {
        try {
            $response = \Xendit\Disbursements::create($request->all());
            return $this->httpSuccess($response);
        } catch (\Exception $e) {
            return $this->httpError($e->getMessage(), $e->getCode());
        }
    }
}
