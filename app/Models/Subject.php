<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
<<<<<<< HEAD
        'name', 'code', 'description', 'type', 'status'
=======
        'name', 'school_id', 'code', 'description', 'type', 'status'
>>>>>>> c356163 (video call ui setup)
    ];
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }


    // Relationships
    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class);
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