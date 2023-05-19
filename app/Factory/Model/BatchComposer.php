<?php
namespace App\Factory\Model;

use Illuminate\Database\Eloquent\Model;

class BatchComposer
{

    protected $uniqueWhere = [];
    protected $uniqueWhereStr = [];
    protected $filterIn;
    protected $caseWhenTpl;
    protected $bindParams;
    protected $chunkSize = 0;
    private $model;
    private $properties;
    private $uniqueColumnName;
    private $tableName;

    private function makeFilter()
    {
        $properties = $this->properties;
        $uniqueColumnName = $this->uniqueColumnName;
        $counter = 0;
        foreach($uniqueColumnName as $uniqueCol) {
            $whereIn = $this->whereInStringFromatValue($properties, $uniqueCol);
            if( $counter == 0 ) {
                $this->filterIn .=  " WHERE ". $uniqueCol. " IN (".$whereIn.")";
                $this->caseWhenTpl .= " WHEN ".$uniqueCol. "= '%s'";
            }else{
                $this->filterIn .=  " AND ". $uniqueCol. " IN (".$whereIn.")";
                $this->caseWhenTpl .= " AND ".$uniqueCol. "= '%s'";
            }
            $counter++;
        }

    }

    public function makeCaseWhen()
    {
        $properties = $this->properties;
        $finalData = [];
        foreach($properties as $key => $value) 
        {   
                $unq = [];
                $row = $value;
                foreach($this->uniqueColumnName as $uniqueColumn) {
                    $unq[] = $row[$uniqueColumn];
                    unset($row[$uniqueColumn]);
                }

                foreach($row as $filedName => $filedValue) {
                    $finalData[$filedName][] = sprintf($this->caseWhenTpl, ...$unq )." THEN ? ";
                    $this->bindParams[$filedName][] = $filedValue;
                }
                
        }
        return $finalData;
    }

    public function setUpdate($caseWhenOptions)
    {
        $caseString="";
        foreach($caseWhenOptions as $fileName => $filedValue ) {
            $str = "CASE ". implode(" ", $filedValue)." ELSE ".$fileName." END, ";
            $caseString .= $fileName . "=".$str;
        }
        
        $caseWhenAlgo =  substr(rtrim($caseString), 0, -1); 
        return $caseWhenAlgo;
    }

    public function execute(Model $model, $tableName, $properties, $uniqueColumnName)  {
        $this->model = $model;
        $this->tableName = $tableName;
        $this->properties = $properties;
        $this->uniqueColumnName = $uniqueColumnName;
       
        $this->makeFilter();
        $caseWhenOptions = $this->makeCaseWhen(); 
        $cases = $this->setUpdate($caseWhenOptions);
        $sql = "UPDATE ".$this->tableName. " SET  updated_at='".date("Y-m-d H:i:s")."', ".$cases."  ".$this->filterIn;
        return ["sql" => $sql, "params" => $this->getBindParams()];
    }
    

    public function whereInStringFromatValue($props, $uniqueCol)
    {
        $values = array_column($this->properties, $uniqueCol);
        $result = "'" . implode("','", $values) . "'";
        $this->uniqueWhere[$uniqueCol][0] = $values;
        $this->uniqueWhere[$uniqueCol][1] = $result;
        return  "'" . implode("','", $values) . "'";
        
    }

    private function getBindParams()
    {
        $finalParams = [];
        foreach($this->bindParams as $key => $paramValue) {
            $finalParams = array_merge($finalParams, $paramValue);
        }
        return $finalParams;
    }

    public function setModel()
    {
        
    }
    public function result()
    {

    }
}