<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaryUpdate extends Model
{
    protected $fillable = [
        'student_id', 'entry_date', 'mood', 'sleep_hours', 'breakfast',
        'lunch', 'dinner', 'physical_activity', 'notes'
    ];

    protected $casts = [
        'entry_date' => 'date',
        'sleep_hours' => 'decimal:1',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}