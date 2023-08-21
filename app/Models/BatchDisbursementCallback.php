<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchDisbursementCallback extends Model
{
    public $incrementing = false;
    protected $keyType = "string";
    protected $guarded = [];

    public function data()
    {
        return $this->hasMany(BatchDisbursementCallback::class, "id", "batch_disbursement_callback_id");
    }

    public function disbursement()
    {
        return $this->belongsTo(BatchDisbursement::class, "reference", "reference");
    }
}
