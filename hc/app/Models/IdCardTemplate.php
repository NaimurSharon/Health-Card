<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdCardTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'background_image',
        'width',
        'height',
        'orientation',
        'design_config',
        'is_active',
        'description'
    ];

    protected $casts = [
        'design_config' => 'array',
        'is_active' => 'boolean',
        'width' => 'integer',
        'height' => 'integer',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Relationships
    public function idCards()
    {
        return $this->hasMany(IdCard::class, 'template_id');
    }

    // Accessors
    public function getBackgroundImageUrlAttribute()
    {
        if ($this->background_image) {
            return asset('public/storage/' . $this->background_image);
        }
        return null;
    }

    public function getDimensionsAttribute()
    {
        return $this->width . 'x' . $this->height . ' ' . $this->orientation;
    }
}