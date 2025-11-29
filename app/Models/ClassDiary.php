<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassDiary extends Model
{
    protected $fillable = [
        'teacher_id',
        'class_id', 
        'section_id',
        'subject_id',
<<<<<<< HEAD
=======
        'school_id',
>>>>>>> c356163 (video call ui setup)
        'entry_date',
        'homework_title',
        'homework_description',
        'due_date',
        'attachments',
        'status'
    ];

    protected $casts = [
        'entry_date' => 'date',
        'due_date' => 'date',
        'attachments' => 'array'
    ];

    // Relationships
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
>>>>>>> c356163 (video call ui setup)

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForStudent($query, $student)
    {
        return $query->where('class_id', $student->class_id)
                    ->where('section_id', $student->section_id);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('due_date', '>=', now()->format('Y-m-d'))
                    ->orWhereNull('due_date');
    }
}