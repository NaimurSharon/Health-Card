<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editor extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'status',
        'created_by',
        'slug',
        'excerpt',
        'featured_image',
        'meta_title',
        'meta_description',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        return Str::limit(strip_tags($this->content), 150);
    }

    // Mutators
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Check if content is published
    public function isPublished()
    {
        return $this->status === 'published';
    }

    // Get published date
    public function getPublishedDateAttribute()
    {
        return $this->published_at ?? $this->created_at;
    }
}