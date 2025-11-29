<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarshipApplication extends Model
{
    protected $fillable = [
        'scholarship_exam_id', 'student_id', 'application_date',
        'status', 'notes'
    ];

    protected $casts = [
        'application_date' => 'date',
    ];

    // Relationships
    public function scholarshipExam()
    {
        return $this->belongsTo(ScholarshipExam::class, 'scholarship_exam_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // Access user through student
    public function user()
    {
        return $this->hasOneThrough(
            User::class, 
            Student::class,
            'id', // Foreign key on students table
            'id', // Foreign key on users table  
            'student_id', // Local key on scholarship_applications table
            'user_id' // Local key on students table
        );
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'applied' => 'Applied',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'waiting_list' => 'Waiting List',
            default => 'Unknown'
        };
    }
}