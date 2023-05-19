<?php

namespace App\Factory\Model\Abstracts;

use Illuminate\Database\Eloquent\Model;

abstract class BaseBatchUpdate{

    protected $model;
    protected $properties;
    protected $uniqueColumns;
    protected $tableName;
    abstract public function execute() : \App\Factory\Model\Contract\BatchUpdateInterface;

    public function model(Model $model, array $props = [], array $uniqueColumns = []) 
    {
        if($model instanceof $model) {
            $this->model = $model;
            $this->properties = $props;
            $this->uniqueColumns = $uniqueColumns;
            $this->tableName = $model->getTable();
            // $this->uniqueColumns = $uniqueColumns;
            return $this->execute();
        } 
        throw new \Exception("Error");
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get properties
     * @return array $properties
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Get unique column name
     * @return array $uniqueColumns
     */
    public function getUniqueColumnName()
    {
        return $this->uniqueColumns;
    }

    /**
     * Get table name in the model
     * @return string $tableName
     */
    public function getTableName()
    {
        return $this->tableName;
    }
    
}