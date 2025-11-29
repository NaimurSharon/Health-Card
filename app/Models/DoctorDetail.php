<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'consultation_fee',
        'follow_up_fee',
        'emergency_fee',
        'license_number',
        'experience',
        'bio',
        'specializations',
        'languages',
        'department',
        'designation',
        'max_patients_per_day',
        'is_available',
        'signature'
    ];

    protected $casts = [
        'consultation_fee' => 'decimal:2',
        'follow_up_fee' => 'decimal:2',
        'emergency_fee' => 'decimal:2',
        'specializations' => 'array',
        'languages' => 'array',
        'is_available' => 'boolean',
        'max_patients_per_day' => 'integer',
        'signature' => 'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function availabilities()
    {
        return $this->hasMany(DoctorAvailability::class, 'doctor_id', 'user_id');
    }

    public function leaveDates()
    {
        return $this->hasMany(DoctorLeaveDate::class, 'doctor_id', 'user_id');
    }

    // Accessor for formatted fees
    public function getFormattedConsultationFeeAttribute()
    {
        return '৳' . number_format($this->consultation_fee, 2);
    }

    public function getFormattedFollowUpFeeAttribute()
    {
        return $this->follow_up_fee ? '৳' . number_format($this->follow_up_fee, 2) : null;
    }

    public function getFormattedEmergencyFeeAttribute()
    {
        return $this->emergency_fee ? '৳' . number_format($this->emergency_fee, 2) : null;
    }

    // Bangladeshi region based methods
    public function getRegionAttribute()
    {
        $hospital = $this->user->hospital;
        if (!$hospital) return 'Unknown';
        
        $address = $hospital->address;
        if (str_contains($address, 'ঢাকা') || str_contains($address, 'Dhaka')) {
            return 'Dhaka';
        } elseif (str_contains($address, 'চট্টগ্রাম') || str_contains($address, 'Chittagong')) {
            return 'Chittagong';
        } elseif (str_contains($address, 'সিলেট') || str_contains($address, 'Sylhet')) {
            return 'Sylhet';
        } elseif (str_contains($address, 'রাজশাহী') || str_contains($address, 'Rajshahi')) {
            return 'Rajshahi';
        } elseif (str_contains($address, 'খুলনা') || str_contains($address, 'Khulna')) {
            return 'Khulna';
        } elseif (str_contains($address, 'বরিশাল') || str_contains($address, 'Barishal')) {
            return 'Barishal';
        } elseif (str_contains($address, 'রংপুর') || str_contains($address, 'Rangpur')) {
            return 'Rangpur';
        } else {
            return 'Other';
        }
    }
    
}