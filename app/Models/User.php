<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'hospital_id','password', 'phone', 'address', 'date_of_birth', 
        'gender', 'profile_image', 'role', 'specialization', 'qualifications', 
        'school_id', 'status'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
    
    public function idCards()
    {
        return $this->hasMany(IdCard::class, 'user_id');
    }
    
    // public function students()
    // {
    //     return $this->hasMany(Student::class, 'parent_id');
    // }
    
    // students renamed to children 
    public function children()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    public function teacherSections()
    {
        return $this->hasMany(Section::class, 'teacher_id');
    }

    public function teacherSubjects()
    {
        return $this->hasMany(ClassSubject::class, 'teacher_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'student_id');
    }

    public function recordedMedicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'recorded_by');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function healthTips()
    {
        return $this->hasMany(HealthTip::class, 'published_by');
    }

    public function publishedHealthTips()
    {
        return $this->hasMany(HealthTip::class, 'published_by');
    }

    public function notices()
    {
        return $this->hasMany(Notice::class, 'published_by');
    }

    public function publishedNotices()
    {
        return $this->hasMany(Notice::class, 'published_by');
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class, 'teacher_id');
    }
    
    public function createdExams()
    {
        return $this->hasMany(OnlineExam::class, 'created_by');
    }

    public function examAttempts()
    {
        return $this->hasMany(ExamAttempt::class, 'student_id');
    }

    public function healthCard()
    {
        return $this->hasOne(HealthCard::class, 'student_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subjects', 'teacher_id', 'subject_id')
                    ->withPivot('class_id', 'section_id')
                    ->withTimestamps();
    }

    public function classes()
    {
        return $this->hasManyThrough(
            Classes::class,
            ClassSubject::class,
            'teacher_id', // Foreign key on class_subjects table
            'id', // Foreign key on classes table
            'id', // Local key on users table
            'class_id' // Local key on class_subjects table
        );
    }

    public function sections()
    {
        return $this->hasManyThrough(
            Section::class,
            ClassSubject::class,
            'teacher_id', // Foreign key on class_subjects table
            'id', // Foreign key on sections table
            'id', // Local key on users table
            'section_id' // Local key on class_subjects table
        );
    }

    // Additional relationships for student-specific data
    public function studentClass()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function studentSection()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function studentMedicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'student_id');
    }

    public function studentHealthCard()
    {
        return $this->hasOne(HealthCard::class, 'student_id');
    }

    public function studentExamAttempts()
    {
        return $this->hasMany(ExamAttempt::class, 'student_id');
    }

    // Role check methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isDoctor()
    {
        return $this->role === 'doctor';
    }

    public function isParent()
    {
        return $this->role === 'parent';
    }

    // Attribute accessors
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getRoleBadgeClassAttribute()
    {
        return [
            'admin' => 'bg-purple-100 text-purple-800',
            'teacher' => 'bg-blue-100 text-blue-800',
            'student' => 'bg-green-100 text-green-800',
            'doctor' => 'bg-red-100 text-red-800',
            'parent' => 'bg-orange-100 text-orange-800',
        ][$this->role] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'active' 
            ? 'bg-green-100 text-green-800' 
            : 'bg-red-100 text-red-800';
    }

    public function getFormattedPhoneAttribute()
    {
        return $this->phone ?: 'N/A';
    }

    public function getFormattedGenderAttribute()
    {
        return $this->gender ? ucfirst($this->gender) : 'N/A';
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    // Scopes
    public function scopeDoctors($query)
    {
        return $query->where('role', 'doctor');
    }

    public function scopeTeachers($query)
    {
        return $query->where('role', 'teacher');
    }

    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    public function scopeParents($query)
    {
        return $query->where('role', 'parent');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
    }

    // Statistics methods
    public function getTeacherStatistics()
    {
        return [
            'sections_count' => $this->teacherSections()->count(),
            'subjects_count' => $this->teacherSubjects()->count(),
            'exams_created' => $this->createdExams()->count(),
            'classes_count' => $this->classes()->count(),
        ];
    }

    public function getStudentStatistics()
    {
        return [
            'medical_records_count' => $this->studentMedicalRecords()->count(),
            'exam_attempts_count' => $this->studentExamAttempts()->count(),
            'health_card' => $this->studentHealthCard ? 'Active' : 'None',
        ];
    }

    public function getDoctorStatistics()
    {
        return [
            'medical_records_count' => $this->recordedMedicalRecords()->count(),
            'health_tips_count' => $this->publishedHealthTips()->count(),
            'appointments_count' => $this->appointments()->count(),
        ];
    }

    public function getAdminStatistics()
    {
        return [
            'notices_published' => $this->publishedNotices()->count(),
            'health_tips_published' => $this->publishedHealthTips()->count(),
        ];
    }

    // Business logic methods
    public function canDelete()
    {
        if ($this->isTeacher()) {
            return $this->teacherSections()->count() === 0 && 
                   $this->teacherSubjects()->count() === 0 &&
                   $this->createdExams()->count() === 0;
        }

        if ($this->isStudent()) {
            return $this->studentMedicalRecords()->count() === 0 &&
                   $this->studentExamAttempts()->count() === 0;
        }

        if ($this->isDoctor()) {
            return $this->recordedMedicalRecords()->count() === 0 &&
                   $this->appointments()->count() === 0;
        }

        return true;
    }

    public function getAssignedClasses()
    {
        if (!$this->isTeacher()) {
            return collect();
        }

        return $this->classes()->distinct()->get();
    }

    public function getRecentExamAttempts($limit = 5)
    {
        if (!$this->isStudent()) {
            return collect();
        }

        return $this->studentExamAttempts()
                    ->with('exam')
                    ->latest()
                    ->limit($limit)
                    ->get();
    }

    public function getRecentMedicalRecords($limit = 5)
    {
        if (!$this->isStudent()) {
            return collect();
        }

        return $this->studentMedicalRecords()
                    ->latest('record_date')
                    ->limit($limit)
                    ->get();
    }
}