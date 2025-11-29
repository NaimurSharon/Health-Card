<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'title', 'content', 'priority', 'target_roles',
        'expiry_date', 'published_by', 'status'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'target_roles' => 'array',
    ];

    // Relationships
    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}