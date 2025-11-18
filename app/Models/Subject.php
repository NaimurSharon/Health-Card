<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name', 'code', 'description', 'type', 'status'
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