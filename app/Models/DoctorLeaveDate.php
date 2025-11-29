<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorLeaveDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'leave_date',
        'reason',
        'is_full_day',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'leave_date' => 'date',
        'is_full_day' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i'
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function getFormattedTimeAttribute()
    {
        if ($this->is_full_day) {
            return 'Full Day';
        }
        
        return \Carbon\Carbon::parse($this->start_time)->format('g:i A') . ' - ' . 
               \Carbon\Carbon::parse($this->end_time)->format('g:i A');
    }
}