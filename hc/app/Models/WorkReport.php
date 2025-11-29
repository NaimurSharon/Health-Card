<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'work_date',
        'work_description',
        'task_status',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'work_date' => 'date',
    ];

    // Relationships
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('work_date', [$startDate, $endDate]);
    }

    public function scopeByStaff($query, $staffId)
    {
        return $query->where('staff_id', $staffId);
    }
}