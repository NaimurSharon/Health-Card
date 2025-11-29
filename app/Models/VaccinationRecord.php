<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VaccinationRecord extends Model
{
    protected $fillable = [
        'student_id', 'vaccine_name', 'vaccine_date', 'dose_number',
        'next_due_date', 'administered_by', 'notes'
    ];

    protected $casts = [
        'vaccine_date' => 'date',
        'next_due_date' => 'date',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}