<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarshipExam extends Model
{
    protected $fillable = [
        'title',
        'description', 
        'class_id',
        'subject_id',
        'exam_date',
        'duration_minutes',
        'total_marks',
        'passing_marks',
        'questions',
        'status',
        'created_by'
    ];

    protected $casts = [
        'exam_date' => 'date',
        'duration_minutes' => 'integer',
        'total_marks' => 'integer',
        'passing_marks' => 'integer'
    ];

    // Get total questions count
    public function getTotalQuestionsAttribute()
    {
        $questions = json_decode($this->questions, true);
        return $questions ? count($questions) : 0;
    }

    // Get applicants count
    public function getApplicantsCountAttribute()
    {
        return $this->attempts()->count();
    }

    // Check if exam is active
    public function isActive()
    {
        return $this->status === 'ongoing' && $this->exam_date->isToday();
    }

    // Check if student can take exam
    public function canStudentTakeExam($studentId)
    {
        return !$this->attempts()
            ->where('student_id', $studentId)
            ->whereIn('status', ['submitted', 'graded'])
            ->exists();
    }

    // Relationships
    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class, 'exam_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope for active exams
    public function scopeActive($query)
    {
        return $query->where('status', 'ongoing')
                    ->where('exam_date', today());
    }

    // Scope for upcoming exams
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')
                    ->where('exam_date', '>=', today());
    }
}