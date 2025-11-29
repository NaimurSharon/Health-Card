<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';

    protected $fillable = [
<<<<<<< HEAD
        'name', 'numeric_value', 'shift', 'capacity', 'status'
    ];

    // Relationships
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class);
    }
    
=======
        'school_id', 'name', 'numeric_value', 'shift', 'capacity', 'status'
    ];

    // Relationships

    // Relationship with Sections
    public function sections()
    {
        return $this->hasMany(Section::class, 'class_id');
    }

    // Relationship with Students
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    // Relationship with Class Subjects
    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class, 'class_id');
    }

    // Relationship with School
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    // Relationship with Routines
    public function routines()
    {
        return $this->hasMany(Routine::class, 'class_id');
    }

    // Relationship with Online Exams
    public function onlineExams()
    {
        return $this->hasMany(OnlineExam::class, 'class_id');
    }

    // Count of Sections
    public function getSectionsCountAttribute()
    {
        return $this->sections()->count();
    }

    // Count of Students
    public function getStudentsCountAttribute()
    {
        return $this->students()->count();
    }

    // Scope for Active Classes
>>>>>>> c356163 (video call ui setup)
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
<<<<<<< HEAD


    public function routines()
    {
        return $this->hasMany(Routine::class);
    }

    public function onlineExams()
    {
        return $this->hasMany(OnlineExam::class);
    }
}
=======
}
>>>>>>> c356163 (video call ui setup)
