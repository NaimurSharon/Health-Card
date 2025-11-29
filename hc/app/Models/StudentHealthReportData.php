<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentHealthReportData extends Model
{
    use HasFactory;

    protected $fillable = [
        'health_report_id',
        'field_id',
        'field_value'
    ];

    // Relationships
    public function healthReport()
    {
        return $this->belongsTo(StudentHealthReport::class, 'health_report_id');
    }

    public function field()
    {
        return $this->belongsTo(HealthReportField::class, 'field_id');
    }
}