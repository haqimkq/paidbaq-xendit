<?php

namespace App\Factory\Disbursement\Contract;

interface ExceptionInterface extends \Throwable
{
    public function getErrorCode();
    
    public function getProductionMessage();
}
