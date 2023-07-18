<?php

namespace App\Factory\Model;

use App\Factory\Model\Contract\BatchUpdateInterface;
use App\Factory\Model\Abstracts\BaseBatchUpdate;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class BatchUpdate extends BaseBatchUpdate implements BatchUpdateInterface{

    protected $uniqueWhere = [];
    protected $uniqueWhereStr = [];
    protected $filterIn;
    protected $caseWhenTpl;
    protected $bindParams;
    protected $chunkSize = 0;

    public function chunk($chunkSize = 0) {
        $this->chunkSize = $chunkSize;
        return $this;
    }

    public function execute() : BatchUpdateInterface
    {
        if($this->chunkSize > 0) {
            $chunkedProperties = array_chunk($this->getProperties(), $this->chunkSize);
        } else{
            $chunkedProperties = [$this->getProperties()];
        }

        
        DB::beginTransaction();

        try {

          
            $i = 0;
            foreach($chunkedProperties as $rows) 
            {
                $transactions = (new BatchComposer())->execute($this->model, $this->getTableName(), $rows, $this->getUniqueColumnName());
                DB::update($transactions["sql"], $transactions["params"]);
               
            }

            DB::commit();
        } catch(\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            throw new QueryException($e->getSql(), $e->getBindings(), $e->getPrevious() );
        } catch(\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage(), $e->getCode() , $e->getPrevious());
        }
        return $this;
    }

    public function result()
    {
        
    }
}