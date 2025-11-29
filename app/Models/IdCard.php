<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'user_id',
        'type',
        'card_number',
        'issue_date',
        'expiry_date',
        'status',
        'template_id',
        'qr_code',
        'barcode',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function template()
    {
        return $this->belongsTo(IdCardTemplate::class, 'template_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeStudentCards($query)
    {
        return $query->where('type', 'student');
    }

    public function scopeStaffCards($query)
    {
        return $query->whereIn('type', ['teacher', 'staff', 'medical']);
    }

    // Accessors
    public function getCardHolderNameAttribute()
    {
        if ($this->student_id) {
            return $this->student->user->name ?? 'N/A';
        }
        return $this->user->name ?? 'N/A';
    }

    public function getCardHolderPhotoAttribute()
    {
        if ($this->student_id) {
            return $this->student->user->profile_photo_path ?? null;
        }
        return $this->user->profile_photo_path ?? null;
    }

    public function getIsExpiredAttribute()
    {
        return $this->expiry_date->isPast();
    }

    public function getDaysUntilExpiryAttribute()
    {
        return now()->diffInDays($this->expiry_date, false);
    }
}