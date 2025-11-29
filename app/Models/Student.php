<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
<<<<<<< HEAD
        'user_id', 'student_id', 'class_id', 'section_id', 'roll_number',
        'parent_id', 'blood_group', 'allergies', 'medical_conditions',
        'emergency_contact', 'admission_date'
=======
        'user_id', 'student_id','school_id', 'class_id', 'section_id', 'roll_number',
        'parent_id', 'mother_name', 'father_name', 'birth_certificate', 'blood_group', 'allergies', 'medical_conditions',
        'emergency_contact', 'admission_date', 'status'
>>>>>>> c356163 (video call ui setup)
    ];

    protected $casts = [
        'admission_date' => 'date',
    ];
    
    // Relationships
    public function user()
    {
<<<<<<< HEAD
        return $this->belongsTo(User::class);
    }

=======
        return $this->belongsTo(User::class, 'user_id');
    }

    // Fix: Use singular 'class' and specify foreign key
>>>>>>> c356163 (video call ui setup)
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function section()
    {
<<<<<<< HEAD
        return $this->belongsTo(Section::class);
=======
        return $this->belongsTo(Section::class, 'section_id');
>>>>>>> c356163 (video call ui setup)
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function healthCard()
    {
<<<<<<< HEAD
        return $this->hasOne(HealthCard::class);
=======
        return $this->hasOne(HealthCard::class, 'student_id');
>>>>>>> c356163 (video call ui setup)
    }

    public function medicalRecords()
    {
<<<<<<< HEAD
        return $this->hasMany(MedicalRecord::class);
=======
        return $this->hasMany(MedicalRecord::class, 'student_id');
>>>>>>> c356163 (video call ui setup)
    }

    public function vaccinationRecords()
    {
<<<<<<< HEAD
        return $this->hasMany(VaccinationRecord::class);
=======
        return $this->hasMany(VaccinationRecord::class, 'student_id');
>>>>>>> c356163 (video call ui setup)
    }

    public function diaryUpdates()
    {
<<<<<<< HEAD
        return $this->hasMany(DiaryUpdate::class);
=======
        return $this->hasMany(DiaryUpdate::class, 'student_id');
>>>>>>> c356163 (video call ui setup)
    }

    public function appointments()
    {
<<<<<<< HEAD
        return $this->hasMany(Appointment::class);
=======
        return $this->hasMany(Appointment::class, 'student_id');
>>>>>>> c356163 (video call ui setup)
    }

    public function examAttempts()
    {
<<<<<<< HEAD
        return $this->hasMany(ExamAttempt::class);
=======
        return $this->hasMany(ExamAttempt::class, 'student_id');
>>>>>>> c356163 (video call ui setup)
    }

    public function scholarshipApplications()
    {
<<<<<<< HEAD
        return $this->hasMany(ScholarshipApplication::class);
=======
        return $this->hasMany(ScholarshipApplication::class, 'student_id');
>>>>>>> c356163 (video call ui setup)
    }

    public function treatmentRequests()
    {
<<<<<<< HEAD
        return $this->hasMany(TreatmentRequest::class);
=======
        return $this->hasMany(TreatmentRequest::class, 'student_id');
    }

    // Relationship with School
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
>>>>>>> c356163 (video call ui setup)
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}