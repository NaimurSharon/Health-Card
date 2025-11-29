<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
<<<<<<< HEAD
        'class_id', 'name', 'room_number', 'teacher_id', 'capacity', 'status'
=======
        'class_id', 'name', 'room_number', 'teacher_id', 'capacity', 'status', 'school_id'
>>>>>>> c356163 (video call ui setup)
    ];
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

<<<<<<< HEAD

    public function class()
    {
        return $this->belongsTo(Classes::class);
=======
    // Explicitly specify the foreign key
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
>>>>>>> c356163 (video call ui setup)
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
<<<<<<< HEAD
        return $this->hasMany(Student::class);
=======
        return $this->hasMany(Student::class, 'section_id');
>>>>>>> c356163 (video call ui setup)
    }

    public function classSubjects()
    {
<<<<<<< HEAD
        return $this->hasMany(ClassSubject::class);
=======
        return $this->hasMany(ClassSubject::class, 'section_id');
>>>>>>> c356163 (video call ui setup)
    }

    public function routines()
    {
<<<<<<< HEAD
        return $this->hasMany(Routine::class);
=======
        return $this->hasMany(Routine::class, 'section_id');
    }

    // Relationship with School
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
>>>>>>> c356163 (video call ui setup)
    }
}