<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'name', 'code', 'type', 'established_year', 'address', 'city', 'district', 'division',
        'phone', 'email', 'website', 'principal_name', 'principal_phone', 'principal_email',
        'logo', 'cover_image', 'school_image', 'motto', 'vision', 'mission', 'academic_system',
        'medium', 'total_students', 'total_teachers', 'total_staff', 'campus_area', 'facilities',
        'accreditations', 'social_links', 'status'
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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Accessors
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    public function getCoverImageUrlAttribute()
    {
        return $this->cover_image ? asset('storage/' . $this->cover_image) : null;
    }

    public function getSchoolImageUrlAttribute()
    {
        return $this->school_image ? asset('storage/' . $this->school_image) : null;
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
}