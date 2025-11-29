<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    protected $fillable = [
<<<<<<< HEAD
        'class_id', 'section_id', 'subject_id', 'teacher_id',
=======
        'class_id', 'section_id', 'school_id', 'subject_id', 'teacher_id',
>>>>>>> c356163 (video call ui setup)
        'day_of_week', 'period', 'start_time', 'end_time',
        'room', 'academic_year'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

<<<<<<< HEAD
    // Relationships
    public function class()
    {
        return $this->belongsTo(Classes::class);
=======
    // Relationships with explicit foreign keys
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
>>>>>>> c356163 (video call ui setup)
    }

    public function section()
    {
<<<<<<< HEAD
        return $this->belongsTo(Section::class);
=======
        return $this->belongsTo(Section::class, 'section_id');
>>>>>>> c356163 (video call ui setup)
    }

    public function subject()
    {
<<<<<<< HEAD
        return $this->belongsTo(Subject::class);
=======
        return $this->belongsTo(Subject::class, 'subject_id');
>>>>>>> c356163 (video call ui setup)
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
<<<<<<< HEAD
=======

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    // Scope for school
    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }
>>>>>>> c356163 (video call ui setup)
}