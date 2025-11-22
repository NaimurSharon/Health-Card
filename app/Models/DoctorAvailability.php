<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_available',
        'slot_duration',
        'max_appointments'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_available' => 'boolean',
        'slot_duration' => 'integer',
        'max_appointments' => 'integer'
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    // Helper methods
    public function getTimeSlots()
    {
        $slots = [];
        $current = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);

        while ($current < $end) {
            $slotEnd = $current->copy()->addMinutes($this->slot_duration);
            if ($slotEnd <= $end) {
                $slots[] = [
                    'start' => $current->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                    'display' => $current->format('g:i A') . ' - ' . $slotEnd->format('g:i A')
                ];
            }
            $current->addMinutes($this->slot_duration);
        }

        return $slots;
    }

    public function getFormattedTimeAttribute()
    {
        return \Carbon\Carbon::parse($this->start_time)->format('g:i A') . ' - ' . 
               \Carbon\Carbon::parse($this->end_time)->format('g:i A');
    }

    // Bangladeshi time format
    public function getBangladeshiTimeAttribute()
    {
        $start = \Carbon\Carbon::parse($this->start_time)->format('g:i A');
        $end = \Carbon\Carbon::parse($this->end_time)->format('g:i A');
        return $start . ' - ' . $end;
    }
}