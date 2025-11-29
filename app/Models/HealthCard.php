<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'card_number',
        'issue_date',
        'expiry_date',
        'status',
        'qr_code',
        'barcode',
        'medical_summary',
        'emergency_instructions',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    // Helper methods
    public function getIsExpiredAttribute()
    {
        return $this->expiry_date < now();
    }

    public function getDaysUntilExpiryAttribute()
    {
        return now()->diffInDays($this->expiry_date, false);
    }
}