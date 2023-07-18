<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchDisbursementData extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function header()
    {
        return $this->belongsTo(BatchDisbursement::class);
    }

    
}
