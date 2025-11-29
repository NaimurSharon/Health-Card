<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentHealthReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'checkup_date',
        'checked_by',
        'created_by',
        'school_id'
    ];

    protected $casts = [
        'checkup_date' => 'date',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function reportData()
    {
        return $this->hasMany(StudentHealthReportData::class, 'health_report_id');
    }
    
    public function data()
    {
        return $this->hasMany(StudentHealthReportData::class, 'health_report_id');
    }

    // Helper methods to get field values
    public function getFieldValue($fieldName)
    {
        $field = HealthReportField::where('field_name', $fieldName)->first();
        if (!$field) return null;

        $data = $this->reportData()->where('field_id', $field->id)->first();
        return $data ? $data->field_value : null;
    }

    public function setFieldValue($fieldName, $value)
    {
        $field = HealthReportField::where('field_name', $fieldName)->first();
        if (!$field) return false;

        return $this->reportData()->updateOrCreate(
            ['field_id' => $field->id],
            ['field_value' => $value]
        );
    }

    // Get all data as array
    public function getDataArray()
    {
        $data = [];
        foreach ($this->reportData as $item) {
            $data[$item->field->field_name] = $item->field_value;
        }
        return $data;
    }
}