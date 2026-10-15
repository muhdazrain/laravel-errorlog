<?php

namespace Azrain\LaravelErrorLog\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLogEvent extends Model
{
    protected $table = 'errorlog_events';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'occurred_at' => 'datetime',
            'line' => 'integer',
            'user_id' => 'integer',
        ];
    }
}
