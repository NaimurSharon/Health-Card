<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    protected $fillable = [
        'exam_id',
        'student_id', 
        'started_at',
        'submitted_at',
        'answers',
        'score',
        'status',
        'time_remaining'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'answers' => 'array',
        'score' => 'decimal:2',
        'time_remaining' => 'integer'
    ];

    // Calculate score based on answers
    public function calculateScore()
    {
        if (!$this->exam || !$this->exam->questions) {
            return 0;
        }
    
        $score = 0;
        $questions = json_decode($this->exam->questions, true);
        
        if (!$questions || !is_array($questions)) {
            return 0;
        }
        
        // Ensure answers is an array
        $answers = $this->answers;
        if (!is_array($answers)) {
            $answers = [];
        }
        
        foreach ($questions as $index => $question) {
            // Skip if answer is not set or is penalty (-1)
            if (!isset($answers[$index]) || $answers[$index] === -1) {
                continue;
            }
            
            // Check if answer matches correct answer
            if ($answers[$index] == $question['correct_answer']) {
                $score += $question['marks'] ?? 1;
            }
        }
    
        return $score;
    }
    
    // Get current question index
    public function getCurrentQuestionIndex()
    {
        $answers = $this->answers;
        if (!is_array($answers)) {
            return 0;
        }
        
        // Count only answers that are not null
        $answeredCount = count(array_filter($answers, function($answer) {
            return $answer !== null && $answer !== '';
        }));
        
        return $answeredCount;
    }

    // Check if exam is completed
    public function isCompleted()
    {
        return $this->status === 'submitted' || $this->status === 'graded';
    }

    // Get time remaining
    public function getTimeRemaining()
    {
        if ($this->submitted_at) {
            return 0;
        }

        $totalSeconds = $this->exam->duration_minutes * 60;
        $elapsed = now()->diffInSeconds($this->started_at);
        $remaining = max(0, $totalSeconds - $elapsed);

        return $remaining;
    }

    // Relationship with exam
    public function exam()
    {
        return $this->belongsTo(ScholarshipExam::class, 'exam_id');
    }

    // Relationship with student
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // Accessor to ensure answers is always treated as array
    public function getAnswersAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }
        
        return json_decode($value, true) ?? [];
    }

    // Mutator to ensure answers is stored as JSON
    public function setAnswersAttribute($value)
    {
        $this->attributes['answers'] = is_string($value) ? $value : json_encode($value ?? []);
    }
}