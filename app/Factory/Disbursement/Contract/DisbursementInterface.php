<?php 
namespace App\Factory\Disbursement\Contract;

use App\Http\Requests\BatchDisbursementStoreRequest;
use Illuminate\Http\Request;

interface DisbursementInterface {

    public function resolve(AdapterInterface $adapter);

    public function make(string $className, BatchDisbursementStoreRequest $request);

    public function getResponse();

    public function saveResponse();

}