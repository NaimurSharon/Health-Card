<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    use HasFactory;

    protected $table = 'website_settings';

    protected $fillable = [
        'section',
        'key_name',
        'key_value',
        'data_type',
        'description',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get setting value by section and key
     */
    public static function getValue($section, $key, $default = null)
    {
        $setting = static::where('section', $section)
                        ->where('key_name', $key)
                        ->where('is_active', true)
                        ->first();

        if (!$setting) {
            return $default;
        }

        // Handle different data types
        switch ($setting->data_type) {
            case 'json':
                return json_decode($setting->key_value, true) ?? $default;
            case 'boolean':
                return (bool)$setting->key_value;
            case 'integer':
                return (int)$setting->key_value;
            default:
                return $setting->key_value ?? $default;
        }
    }

    /**
     * Set setting value
     */
    public static function setValue($section, $key, $value, $dataType = 'string', $description = null, $userId = null)
    {
        // Convert value based on data type
        $processedValue = $value;
        switch ($dataType) {
            case 'json':
                $processedValue = json_encode($value);
                break;
            case 'boolean':
                $processedValue = $value ? '1' : '0';
                break;
            case 'integer':
                $processedValue = (string)$value;
                break;
        }

        return static::updateOrCreate(
            [
                'section' => $section,
                'key_name' => $key,
            ],
            [
                'key_value' => $processedValue,
                'data_type' => $dataType,
                'description' => $description,
                'updated_by' => $userId ?? auth()->id(),
                'created_by' => $userId ?? auth()->id(),
            ]
        );
    }

    /**
     * Get all settings for a section
     */
    public static function getSection($section)
    {
        return static::where('section', $section)
                    ->where('is_active', true)
                    ->get()
                    ->keyBy('key_name');
    }

    /**
     * Relationships
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}