<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogEntry extends Model
{
    protected $table = "log_entries";
    protected $guarded = [];

    protected $casts = [
        'request_body' => 'array',
        'response_body' => 'array'
    ];
}
