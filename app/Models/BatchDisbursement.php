<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchDisbursement extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function data()
    {
        return $this->hasMany(BatchDisbursementData::class);
    }

    public function response()
    {
        return $this->hasOne(BatchDisbursementResponse::class);
    }
    
}
