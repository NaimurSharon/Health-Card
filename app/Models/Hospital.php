<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    protected $fillable = [
        'name', 'type', 'address', 'phone', 'email', 'emergency_contact',
        'website', 'services', 'facilities', 'status'
    ];

    protected $casts = [
        'services' => 'array',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Relationships
    public function doctors()
    {
        return $this->hasMany(User::class)->where('role', 'doctor');
    }

    // Accessors
    public function getServicesListAttribute()
    {
        return $this->services ? implode(', ', $this->services) : 'No services listed';
    }

    public function getFacilitiesListAttribute()
    {
        return $this->facilities ?: 'No facilities listed';
    }

    public function getTypeLabelAttribute()
    {
        switch ($this->type) {
            case 'government':
                return 'Government';
            case 'private':
                return 'Private';
            case 'specialized':
                return 'Specialized';
            case 'clinic':
                return 'Clinic';
            default:
                return 'Unknown';
        }
    }

}