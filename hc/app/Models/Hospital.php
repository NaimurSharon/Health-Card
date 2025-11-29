<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Hospital extends Model
{
    protected $fillable = [
        'name', 'type', 'address', 'phone', 'email', 'emergency_contact',
        'website', 'youtube_video_url', 'services', 'facilities', 
        'description', 'images', 'status'
    ];

    protected $casts = [
        'services' => 'array',
        'images' => 'array',
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

    public function getImageUrlsAttribute()
    {
        if (!$this->images) {
            return [];
        }

        return array_map(function ($image) {
            return asset('storage/' . $image);
        }, $this->images);
    }

    public function getFirstImageUrlAttribute()
    {
        if (!$this->images || empty($this->images)) {
            return asset('images/hospital-placeholder.jpg');
        }

        return asset('storage/' . $this->images[0]);
    }

    public function getYoutubeEmbedUrlAttribute()
    {
        if (!$this->youtube_video_url) {
            return null;
        }

        // Convert YouTube URL to embed URL
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->youtube_video_url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        return $this->youtube_video_url;
    }
}