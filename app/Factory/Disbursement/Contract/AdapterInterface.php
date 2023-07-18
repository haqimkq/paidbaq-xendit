<?php 
namespace App\Factory\Disbursement\Contract;

use Xendit\Exceptions\ApiException;

interface AdapterInterface {

    public function storeRequest();

    public function getRequest();

    public function storeResponse(array $response);

    public function handleApiError(ApiException $exception);

    public function getCorrelationId();

    public function getRequestHeader();
    
    public function getRequestBody();


}