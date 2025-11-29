<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthTip extends Model
{
    protected $fillable = [
        'title', 'content', 'category', 'target_audience',
        'published_by', 'status'
    ];

    // Cast the category to ensure proper data handling
    protected $casts = [
        'category' => 'string',
        'target_audience' => 'string',
        'status' => 'string',
    ];

    // Relationships
    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}