<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Backup extends Model
{
    use HasFactory;

    protected $table = 'backups'; // ✅ Table name

    protected $fillable = [
        'file_name',
        'file_size',
        'file_path',
    ];

    public $timestamps = true; 
}