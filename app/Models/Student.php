<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'student_id',
        'school_id',
        'class_id',
        'section_id',
        'roll_number',
        'parent_id',
        'mother_name',
        'father_name',
        'birth_certificate',
        'blood_group',
        'allergies',
        'medical_conditions',
        'emergency_contact',
        'admission_date',
        'status'
    ];

    protected $casts = [
        'admission_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Fix: Use singular 'class' and specify foreign key
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function healthCard()
    {
        return $this->hasOne(HealthCard::class, 'user_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'user_id');
    }

    public function vaccinationRecords()
    {
        return $this->hasMany(VaccinationRecord::class, 'student_id');
    }

    public function diaryUpdates()
    {
        return $this->hasMany(DiaryUpdate::class, 'student_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'student_id');
    }

    public function examAttempts()
    {
        return $this->hasMany(ExamAttempt::class, 'student_id');
    }

    public function scholarshipApplications()
    {
        return $this->hasMany(ScholarshipApplication::class, 'student_id');
    }

    public function treatmentRequests()
    {
        return $this->hasMany(TreatmentRequest::class, 'student_id');
    }

    // Relationship with School
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}