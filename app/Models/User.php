<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'hospital_id',
        'password',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'profile_image',
        'role',
        'specialization',
        'qualifications',
        'school_id',
        'status',
        'principal_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'qualifications' => 'array',
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Assigned doctor relationship (schools where this doctor is assigned)
    public function assignedSchools()
    {
        return $this->hasMany(School::class, 'assigned_doctor');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function idCards()
    {
        return $this->hasMany(IdCard::class, 'user_id');
    }

    public function children()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    // Principal relationship
    public function managedSchool()
    {
        return $this->hasOne(School::class, 'principal_id');
    }

    // DOCTOR 
    public function doctorDetail()
    {
        return $this->hasOne(DoctorDetail::class, 'user_id');
    }

    public function doctorAvailabilities()
    {
        return $this->hasMany(DoctorAvailability::class, 'doctor_id');
    }

    public function doctorLeaveDates()
    {
        return $this->hasMany(DoctorLeaveDate::class, 'doctor_id');
    }

    public function appointmentsAsDoctor()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function scopeActiveDoctors($query)
    {
        return $query->where('role', 'doctor')->where('status', 'active');
    }

    public function scopeByRegion($query, $region)
    {
        return $query->whereHas('hospital', function ($q) use ($region) {
            $q->where('address', 'like', '%' . $region . '%');
        });
    }

    // Helper methods for doctors
    public function isAvailableOn($date)
    {
        if (!$this->doctorDetail || !$this->doctorDetail->is_available) {
            return false;
        }

        $dayOfWeek = strtolower(\Carbon\Carbon::parse($date)->englishDayOfWeek);
        $isOnLeave = $this->doctorLeaveDates()
            ->where('leave_date', $date)
            ->exists();

        if ($isOnLeave) {
            return false;
        }

        return $this->doctorAvailabilities()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->exists();
    }

    public function getAvailableTimeSlots($date)
    {
        if (!$this->isAvailableOn($date)) {
            return [];
        }

        $dayOfWeek = strtolower(\Carbon\Carbon::parse($date)->englishDayOfWeek);
        $availability = $this->doctorAvailabilities()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$availability) {
            return [];
        }

        return $availability->getTimeSlots();
    }

    // Bangladeshi doctor specific methods
    public function getBangladeshiRegionAttribute()
    {
        if (!$this->hospital)
            return 'Unknown';

        $address = $this->hospital->address;
        if (str_contains($address, 'ঢাকা') || str_contains(strtolower($address), 'dhaka')) {
            return 'Dhaka';
        } elseif (str_contains($address, 'চট্টগ্রাম') || str_contains(strtolower($address), 'chittagong')) {
            return 'Chittagong';
        } elseif (str_contains($address, 'সিলেট') || str_contains(strtolower($address), 'sylhet')) {
            return 'Sylhet';
        } else {
            return 'Other Division';
        }
    }

    public function getFormattedFeesAttribute()
    {
        if (!$this->doctorDetail)
            return null;

        return [
            'consultation' => $this->doctorDetail->formatted_consultation_fee,
            'follow_up' => $this->doctorDetail->formatted_follow_up_fee,
            'emergency' => $this->doctorDetail->formatted_emergency_fee
        ];
    }

    public function principal()
    {
        return $this->hasOne(Teacher::class, 'user_id')
            ->whereHas('user', function ($query) {
                $query->where('role', 'principal');
            });
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id');
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

    public function isPrincipal()
    {
        return $this->role === 'principal';
    }

    // Principal specific methods
    public function canManageSchool(School $school = null)
    {
        if (!$this->isPrincipal()) {
            return false;
        }

        if ($school) {
            return $this->managedSchool && $this->managedSchool->id === $school->id;
        }

        return $this->managedSchool !== null;
    }

    public function getManagedSchoolAttribute()
    {
        return $this->managedSchool()->first();
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
            'principal' => 'bg-indigo-100 text-indigo-800',
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

    public function getProfileImageUrlAttribute()
    {
        return $this->profile_image ? asset('storage/' . $this->profile_image) : null;
    }

    public function getQualificationsListAttribute()
    {
        if (!$this->qualifications) {
            return 'No qualifications';
        }

        return is_array($this->qualifications)
            ? implode(', ', $this->qualifications)
            : $this->qualifications;
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

    public function scopePrincipals($query)
    {
        return $query->where('role', 'principal');
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

    public function scopeWithoutSchool($query)
    {
        return $query->whereNull('school_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('phone', 'like', "%{$search}%");
    }

    public function scopeAvailablePrincipals($query)
    {
        return $query->where('role', 'principal')
            ->whereDoesntHave('managedSchool');
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

    public function getPrincipalStatistics()
    {
        if (!$this->managedSchool) {
            return [];
        }

        return [
            'school_name' => $this->managedSchool->name,
            'total_students' => $this->managedSchool->getStudentCountAttribute(),
            'total_teachers' => $this->managedSchool->getTeacherCountAttribute(),
            'total_classes' => $this->managedSchool->classes()->count(),
            'years_established' => $this->managedSchool->getYearsOperatingAttribute(),
        ];
    }

    // Business logic methods
    public function canDelete()
    {
        if ($this->isPrincipal() && $this->managedSchool) {
            return false; // Cannot delete principal who manages a school
        }

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

    public function canBePrincipal()
    {
        return $this->isPrincipal() && !$this->managedSchool;
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

    public function getPrincipalViewData()
    {
        if (!$this->isPrincipal() || !$this->managedSchool) {
            return null;
        }

        return [
            'school' => $this->managedSchool,
            'principal' => $this,
            'statistics' => $this->getPrincipalStatistics(),
            'recent_activities' => $this->getRecentActivities(),
        ];
    }

    protected function getRecentActivities()
    {
        // Implement recent activities logic for principal dashboard
        return [
            'notices' => $this->publishedNotices()->latest()->limit(5)->get(),
            'health_tips' => $this->publishedHealthTips()->latest()->limit(5)->get(),
        ];
    }
}