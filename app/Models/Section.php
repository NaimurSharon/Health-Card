<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'class_id', 'name', 'room_number', 'teacher_id', 'capacity', 'status'
    ];
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }


    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class);
    }

    public function routines()
    {
        return $this->hasMany(Routine::class);
    }
}