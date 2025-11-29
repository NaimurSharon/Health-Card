<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'class_id', 'name', 'room_number', 'teacher_id', 'capacity', 'status', 'school_id'
    ];
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Explicitly specify the foreign key
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'section_id');
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class, 'section_id');
    }

    public function routines()
    {
        return $this->hasMany(Routine::class, 'section_id');
    }

    // Relationship with School
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
}