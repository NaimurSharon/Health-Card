<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthReportField extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'label',
        'field_type',
        'field_name',
        'options',
        'validation_rules',
        'placeholder',
        'is_required',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(HealthReportCategory::class, 'category_id');
    }

    public function reportData()
    {
        return $this->hasMany(StudentHealthReportData::class, 'field_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('label');
    }

    // Helper methods
    public function getValidationRulesAttribute()
    {
        $rules = [];
        
        if ($this->is_required) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        switch ($this->field_type) {
            case 'number':
                $rules[] = 'numeric';
                break;
            case 'date':
                $rules[] = 'date';
                break;
            case 'select':
                if ($this->options) {
                    $rules[] = 'in:' . implode(',', $this->options);
                }
                break;
        }

        return implode('|', $rules);
    }
}