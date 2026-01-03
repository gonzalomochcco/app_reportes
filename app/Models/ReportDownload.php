<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportDownload extends Model
{
    protected $fillable = [
        'filename',
        'status',
        'downloaded',
        'expires_at',
    ];

    protected $casts = [
        'downloaded' => 'boolean',
        'expires_at' => 'datetime',
    ];
}
