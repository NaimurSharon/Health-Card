<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';

    protected $fillable = [
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
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }


    public function routines()
    {
        return $this->hasMany(Routine::class);
    }

    public function onlineExams()
    {
        return $this->hasMany(OnlineExam::class);
    }
}