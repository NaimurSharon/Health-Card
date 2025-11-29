<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'name', 'code', 'type', 'established_year', 'address', 'city', 'district', 'division',
        'phone', 'email', 'website', 'principal_id', 'assigned_doctor', 'logo', 'cover_image', 'school_image', 
        'motto', 'vision', 'mission', 'academic_system', 'medium', 'total_students', 
        'total_teachers', 'total_staff', 'campus_area', 'facilities', 'accreditations', 
        'social_links', 'status'
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function classes()
    {
        return $this->hasMany(Classes::class);
    }

    public function principal()
    {
        return $this->belongsTo(User::class, 'principal_id')->where('role', 'principal');
    }

    public function assignedDoctor()
    {
        return $this->belongsTo(User::class, 'assigned_doctor')->where('role', 'doctor');
    }

    public function teachers()
    {
        return $this->hasMany(User::class)->where('role', 'teacher');
    }

    public function students()
    {
        return $this->hasMany(User::class)->where('role', 'student');
    }

    public function admins()
    {
        return $this->hasMany(User::class)->where('role', 'admin');
    }

    public function doctors()
    {
        return $this->hasMany(User::class)->where('role', 'doctor');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByDivision($query, $division)
    {
        return $query->where('division', $division);
    }

    public function scopeByDistrict($query, $district)
    {
        return $query->where('district', $district);
    }

    public function scopeHasPrincipal($query)
    {
        return $query->whereNotNull('principal_id');
    }

    public function scopeHasAssignedDoctor($query)
    {
        return $query->whereNotNull('assigned_doctor');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('district', 'like', "%{$search}%");
    }

    // Accessors
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('public/storage/' . $this->logo) : null;
    }

    public function getCoverImageUrlAttribute()
    {
        return $this->cover_image ? asset('public/storage/' . $this->cover_image) : null;
    }

    public function getSchoolImageUrlAttribute()
    {
        return $this->school_image ? asset('public/storage/' . $this->school_image) : null;
    }

    public function getPrincipalNameAttribute()
    {
        return $this->principal ? $this->principal->name : null;
    }

    public function getPrincipalEmailAttribute()
    {
        return $this->principal ? $this->principal->email : null;
    }

    public function getPrincipalPhoneAttribute()
    {
        return $this->principal ? $this->principal->phone : null;
    }

    public function getAssignedDoctorNameAttribute()
    {
        return $this->assignedDoctor ? $this->assignedDoctor->name : null;
    }

    public function getAssignedDoctorEmailAttribute()
    {
        return $this->assignedDoctor ? $this->assignedDoctor->email : null;
    }

    public function getAssignedDoctorPhoneAttribute()
    {
        return $this->assignedDoctor ? $this->assignedDoctor->phone : null;
    }

    public function getAssignedDoctorSpecializationsAttribute()
    {
        if (!$this->assignedDoctor || !$this->assignedDoctor->doctorDetail) {
            return null;
        }

        return $this->assignedDoctor->doctorDetail->specializations;
    }

    public function getAssignedDoctorFeesAttribute()
    {
        if (!$this->assignedDoctor || !$this->assignedDoctor->doctorDetail) {
            return null;
        }

        return [
            'consultation' => $this->assignedDoctor->doctorDetail->consultation_fee,
            'follow_up' => $this->assignedDoctor->doctorDetail->follow_up_fee,
            'emergency' => $this->assignedDoctor->doctorDetail->emergency_fee,
        ];
    }

    public function getFormattedAddressAttribute()
    {
        $addressParts = array_filter([
            $this->address,
            $this->city,
            $this->district,
            $this->division
        ]);

        return implode(', ', $addressParts);
    }

    public function getFacilitiesListAttribute()
    {
        return $this->facilities ? implode(', ', $this->facilities) : 'No facilities listed';
    }

    // Methods
    public function getYearsOperatingAttribute()
    {
        return $this->established_year ? date('Y') - $this->established_year : 0;
    }

    public function getStudentCountAttribute()
    {
        return $this->users()->where('role', 'student')->count();
    }

    public function getTeacherCountAttribute()
    {
        return $this->users()->where('role', 'teacher')->count();
    }

    public function getDoctorCountAttribute()
    {
        return $this->users()->where('role', 'doctor')->count();
    }

    public function getPrincipalCountAttribute()
    {
        return $this->principal ? 1 : 0;
    }

    public function getAdminCountAttribute()
    {
        return $this->users()->where('role', 'admin')->count();
    }

    public function canAssignPrincipal(User $user)
    {
        return $user->role === 'principal' && $user->school_id === $this->id;
    }

    public function canAssignDoctor(User $user)
    {
        return $user->role === 'doctor' && $user->school_id === $this->id;
    }

    public function assignPrincipal(User $user)
    {
        if (!$this->canAssignPrincipal($user)) {
            return false;
        }

        $this->principal_id = $user->id;
        return $this->save();
    }

    public function assignDoctor(User $user)
    {
        if (!$this->canAssignDoctor($user)) {
            return false;
        }

        $this->assigned_doctor = $user->id;
        return $this->save();
    }

    public function removePrincipal()
    {
        $this->principal_id = null;
        return $this->save();
    }

    public function removeDoctor()
    {
        $this->assigned_doctor = null;
        return $this->save();
    }

    public function getStatisticsAttribute()
    {
        return [
            'students' => $this->getStudentCountAttribute(),
            'teachers' => $this->getTeacherCountAttribute(),
            'doctors' => $this->getDoctorCountAttribute(),
            'admins' => $this->getAdminCountAttribute(),
            'classes' => $this->classes()->count(),
            'years_operating' => $this->getYearsOperatingAttribute(),
        ];
    }

    public function getMedicalStatisticsAttribute()
    {
        return [
            'assigned_doctor' => $this->assigned_doctor ? $this->assignedDoctorName : 'Not Assigned',
            'doctor_available' => $this->assignedDoctor && $this->assignedDoctor->doctorDetail 
                ? $this->assignedDoctor->doctorDetail->is_available 
                : false,
            'medical_records_count' => MedicalRecord::whereHas('student', function($query) {
                $query->where('school_id', $this->id);
            })->count(),
            'health_cards_count' => HealthCard::whereHas('student', function($query) {
                $query->where('school_id', $this->id);
            })->count(),
        ];
    }

    public function getSocialLinksArrayAttribute()
    {
        if (!$this->social_links) {
            return [];
        }

        return is_array($this->social_links) ? $this->social_links : json_decode($this->social_links, true);
    }

    public function hasSocialLink($platform)
    {
        $links = $this->social_links_array;
        return isset($links[$platform]) && !empty($links[$platform]);
    }

    public function getSocialLink($platform)
    {
        $links = $this->social_links_array;
        return $links[$platform] ?? null;
    }

    // New methods for doctor management
    public function isDoctorAssigned()
    {
        return !is_null($this->assigned_doctor);
    }

    public function getAvailableDoctors()
    {
        return User::where('role', 'doctor')
                  ->where('school_id', $this->id)
                  ->where('status', 'active')
                  ->get();
    }

    public function canScheduleAppointment()
    {
        return $this->isDoctorAssigned() && 
               $this->assignedDoctor->doctorDetail && 
               $this->assignedDoctor->doctorDetail->is_available;
    }

    public function getDoctorAvailability()
    {
        if (!$this->isDoctorAssigned()) {
            return null;
        }

        return $this->assignedDoctor->doctorAvailabilities()
            ->where('is_available', true)
            ->get()
            ->groupBy('day_of_week');
    }

    public function getUpcomingDoctorLeaveDates()
    {
        if (!$this->isDoctorAssigned()) {
            return collect();
        }

        return $this->assignedDoctor->doctorLeaveDates()
            ->where('leave_date', '>=', now())
            ->orderBy('leave_date')
            ->get();
    }
}