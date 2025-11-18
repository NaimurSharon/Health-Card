<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineExam extends Model
{
    protected $fillable = [
        'title', 'description', 'class_id', 'subject_id', 'exam_date',
        'start_time', 'end_time', 'duration_minutes', 'total_marks',
        'passing_marks', 'questions', 'status', 'created_by'
    ];

    protected $casts = [
        'exam_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'questions' => 'array',
    ];

    // Relationships
    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}