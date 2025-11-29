<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualHealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'school_id',
        'age',
        'weight',
        'height',
        'head_circumference',
        'development_notes',
        'difficulties',
        'special_instructions',
        'general_health',
        'vaccination_status',
        'nutrition_notes',
        'recorded_by'
    ];

    protected $casts = [
        'age' => 'integer',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'head_circumference' => 'decimal:2',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Scopes
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForYear($query, $year)
    {
        return $query->where('age', $year);
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('age', 'desc');
    }

    public function scopeOldestFirst($query)
    {
        return $query->orderBy('age', 'asc');
    }

    // Helper methods
    public function calculateBMI()
    {
        if ($this->weight && $this->height && $this->height > 0) {
            $heightInMeters = $this->height / 100;
            return $this->weight / ($heightInMeters * $heightInMeters);
        }
        return null;
    }

    public function getBMICategory()
    {
        $bmi = $this->calculateBMI();
        
        if (!$bmi) return null;

        if ($bmi < 18.5) {
            return ['category' => 'Underweight', 'color' => 'yellow'];
        } elseif ($bmi >= 18.5 && $bmi < 25) {
            return ['category' => 'Normal weight', 'color' => 'green'];
        } elseif ($bmi >= 25 && $bmi < 30) {
            return ['category' => 'Overweight', 'color' => 'orange'];
        } else {
            return ['category' => 'Obese', 'color' => 'red'];
        }
    }

    public function getGrowthStatus()
    {
        // You can implement growth chart logic here based on age and WHO standards
        if ($this->height && $this->weight) {
            // Basic growth assessment logic
            return 'Normal growth';
        }
        return 'Not assessed';
    }
}