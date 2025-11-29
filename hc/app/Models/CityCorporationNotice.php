<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CityCorporationNotice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'priority',
        'target_type',
        'target_schools',
        'target_roles',
        'expiry_date',
        'published_by',
        'status'
    ];

    protected $casts = [
        'target_schools' => 'array',
        'target_roles' => 'array',
        'expiry_date' => 'date',
    ];

    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function schools()
    {
        return $this->belongsToMany(School::class, 'city_corporation_notice_school', 'notice_id', 'school_id');
    }

    // Scope for active notices
    public function scopeActive($query)
    {
        return $query->where('status', 'published')
                    ->where('expiry_date', '>=', now());
    }

    // Scope for notices targeting specific school
    public function scopeForSchool($query, $schoolId)
    {
        return $query->where(function($q) use ($schoolId) {
            $q->where('target_type', 'all_schools')
              ->orWhere(function($q2) use ($schoolId) {
                  $q2->where('target_type', 'specific_schools')
                     ->whereJsonContains('target_schools', (string)$schoolId);
              });
        });
    }

    // Scope for notices targeting specific roles
    public function scopeForRoles($query, $roles)
    {
        return $query->where(function($q) use ($roles) {
            foreach ($roles as $role) {
                $q->orWhereJsonContains('target_roles', $role);
            }
        });
    }

    // Check if notice is applicable for a school
    public function isApplicableForSchool($schoolId)
    {
        if ($this->target_type === 'all_schools') {
            return true;
        }

        if ($this->target_type === 'specific_schools' && $this->target_schools) {
            return in_array((string)$schoolId, $this->target_schools);
        }

        return false;
    }

    // Check if notice is applicable for a role
    public function isApplicableForRole($role)
    {
        return in_array($role, $this->target_roles ?? []);
    }
}