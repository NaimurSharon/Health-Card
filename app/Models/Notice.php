<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'title',
        'school_id',
        'content',
        'priority',
        'target_roles',
        'expiry_date',
        'published_by',
        'published_at',
        'status'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'published_at' => 'datetime',
        'target_roles' => 'array',
    ];

    // Relationships
    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}