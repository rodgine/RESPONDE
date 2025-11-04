<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class IncidentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_code',
        'incident_type',
        'location',
        'landmark_photos',
        'proof_photos',
        'status',
        'date_reported',
        'user_id',
    ];

    protected $casts = [
        'landmark_photos' => 'array',
        'proof_photos' => 'array',
        'date_reported' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            if (empty($report->reference_code)) {
                $report->reference_code = 'INC-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
            }
            if (empty($report->date_reported)) {
                $report->date_reported = now();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incident()
    {
        return $this->hasOne(Incident::class, 'reference_number', 'reference_code');
    }
}
