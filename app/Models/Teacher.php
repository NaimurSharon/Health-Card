<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'school_id',
        'teacher_id',
        'designation',
        'department',
        'nid_number',
        'birth_certificate',
        'marital_status',
        'father_name',
        'mother_name',
        'emergency_contact',
        'blood_group',
        'medical_conditions',
        'allergies',
        'disabilities',
        'class_teacher_of',
        'status',
        'signature', 
    ];

    protected $casts = [
        'medical_conditions' => 'array',
        'allergies' => 'array',
        'disabilities' => 'array',
        'signature' => 'string',
    ];

    protected $attributes = [
        'designation' => 'assistant_teacher',
        'marital_status' => 'single',
        'status' => 'active',
    ];

    /**
     * Get the user associated with the teacher.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the school associated with the teacher.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the class where this teacher is class teacher.
     */
    public function classTeacherOf(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_teacher_of');
    }

    /**
     * Get the sections where this teacher is class teacher.
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'teacher_id');
    }

    /**
     * Get the subjects taught by this teacher.
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(ClassSubject::class, 'teacher_id');
    }

    /**
     * Get the routines for this teacher.
     */
    public function routines(): HasMany
    {
        return $this->hasMany(Routine::class, 'teacher_id');
    }

    /**
     * Get the homeworks assigned by this teacher.
     */
    public function homeworks(): HasMany
    {
        return $this->hasMany(ClassDiary::class, 'teacher_id');
    }

    /**
     * Scope a query to only include active teachers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include teachers of a specific school.
     */
    public function scopeOfSchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    /**
     * Scope a query to only include teachers with specific designation.
     */
    public function scopeByDesignation($query, $designation)
    {
        return $query->where('designation', $designation);
    }

    /**
     * Get the full name of the teacher (from user table).
     */
    public function getFullNameAttribute(): string
    {
        return $this->user->name ?? '';
    }

    /**
     * Get the email of the teacher (from user table).
     */
    public function getEmailAttribute(): string
    {
        return $this->user->email ?? '';
    }

    /**
     * Get the phone number of the teacher (from user table).
     */
    public function getPhoneAttribute(): string
    {
        return $this->user->phone ?? '';
    }

    /**
     * Get the qualifications of the teacher (from user table).
     */
    public function getQualificationsAttribute(): string
    {
        return $this->user->qualifications ?? '';
    }

    /**
     * Get the specialization of the teacher (from user table).
     */
    public function getSpecializationAttribute(): string
    {
        return $this->user->specialization ?? '';
    }

    /**
     * Check if teacher is class teacher of any class.
     */
    public function getIsClassTeacherAttribute(): bool
    {
        return !is_null($this->class_teacher_of);
    }

    /**
     * Get the assigned classes for this teacher.
     */
    public function assignedClasses()
    {
        return $this->hasManyThrough(
            Classes::class,
            ClassSubject::class,
            'teacher_id',
            'id',
            'id',
            'class_id'
        )->distinct();
    }

    /**
     * Get teacher's health records if any.
     */
    public function healthRecords(): HasMany
    {
        return $this->hasMany(TeacherHealthRecord::class, 'teacher_id');
    }

    /**
     * Get teacher's latest health record.
     */
    public function latestHealthRecord(): HasOne
    {
        return $this->hasOne(TeacherHealthRecord::class, 'teacher_id')->latest();
    }
}