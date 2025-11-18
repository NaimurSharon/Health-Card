<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSubject extends Model
{
    protected $fillable = [
        'class_id', 'section_id', 'subject_id', 'teacher_id'
    ];

    // Relationships
    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}