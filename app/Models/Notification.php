<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'message',
        'data',
        'responder_id',
        'read',
    ];

    protected $casts = [
        'data' => 'array',
        'read' => 'boolean',
    ];
}
