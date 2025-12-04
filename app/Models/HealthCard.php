<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Changed from student_id to user_id
        'card_number',
        'issue_date',
        'expiry_date',
        'status',
        'qr_code',
        'barcode',
        'medical_summary',
        'emergency_instructions',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // For backward compatibility - if you still need to access student relationship
    // This assumes student users have role = 'student'
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id')
                    ->where('role', 'student');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '>', now())
                    ->where('expiry_date', '<=', now()->addDays($days));
    }

    public function scopeForRole($query, $role)
    {
        return $query->whereHas('user', function($q) use ($role) {
            $q->where('role', $role);
        });
    }

    // Helper methods
    public function getIsExpiredAttribute()
    {
        return $this->expiry_date < now();
    }

    public function getDaysUntilExpiryAttribute()
    {
        return now()->diffInDays($this->expiry_date, false);
    }

    public function getExpiryStatusAttribute()
    {
        if ($this->is_expired) {
            return 'expired';
        }
        
        if ($this->days_until_expiry <= 30) {
            return 'expiring_soon';
        }
        
        return 'active';
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'green',
            'expired' => 'red',
            'suspended' => 'orange',
            default => 'gray',
        };
    }

    // New methods for user-based access
    public function getUserNameAttribute()
    {
        return $this->user?->name;
    }

    public function getUserEmailAttribute()
    {
        return $this->user?->email;
    }

    public function getUserPhoneAttribute()
    {
        return $this->user?->phone;
    }

    // Static methods
    public static function generateCardNumber()
    {
        $prefix = 'HCB-';
        $latest = self::where('card_number', 'like', $prefix . '%')
                     ->orderBy('card_number', 'desc')
                     ->first();
        
        if ($latest) {
            $number = intval(str_replace($prefix, '', $latest->card_number)) + 1;
        } else {
            $number = 1;
        }
        
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public static function createForUser($userId, array $data = [])
    {
        $cardData = array_merge([
            'user_id' => $userId,
            'card_number' => self::generateCardNumber(),
            'issue_date' => now(),
            'expiry_date' => now()->addYear(),
            'status' => 'active',
        ], $data);

        return self::create($cardData);
    }

    // Override boot method if needed for additional logic
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($healthCard) {
            // Generate card number if not provided
            if (empty($healthCard->card_number)) {
                $healthCard->card_number = self::generateCardNumber();
            }
            
            // Set default issue date if not provided
            if (empty($healthCard->issue_date)) {
                $healthCard->issue_date = now();
            }
        });

        static::updating(function ($healthCard) {
            // Auto-update status based on expiry date
            if ($healthCard->expiry_date < now() && $healthCard->status === 'active') {
                $healthCard->status = 'expired';
            }
        });
    }
}