<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'responder_id',
        'details',
        'action_taken',
        'documentation',
        'victims_count',
        'deaths_count',
        'rescued_count',
        'date_resolved',
    ];

    protected $casts = [
        'documentation' => 'array',
        'date_resolved' => 'datetime',
    ];

    /**
     * Relationship: Responder assigned to the incident
     */
    public function responder()
    {
        return $this->belongsTo(User::class, 'responder_id');
    }

    /**
     * Relationship: Corresponding incident report
     */
    public function report()
    {
        return $this->belongsTo(IncidentReport::class, 'reference_number', 'reference_code');
    }

    /**
     * Relationship: The citizen who filed the report (via IncidentReport)
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,             // Final model
            IncidentReport::class,   // Intermediate model
            'reference_code',        // Foreign key on IncidentReport table that links to Incident
            'id',                    // Foreign key on User table that links to IncidentReport
            'reference_number',      // Local key on Incident
            'user_id'                // Local key on IncidentReport that links to User
        );
    }
}
