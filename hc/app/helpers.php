<?php

use App\Models\Setting;
use App\Models\WebsiteSetting;

if (!function_exists('setting')) {
    /**
     * Get or set a setting value
     *
     * @param string|array|null $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key = null, $default = null)
    {
        if (is_null($key)) {
            return Setting::all();
        }
        
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                Setting::setValue($k, $v);
            }
            return true;
        }
        
        return Setting::getValue($key, $default);
    }
}

if (!function_exists('website_setting')) {
    function website_setting($section, $key = null, $default = null)
    {
        if ($key === null) {
            return WebsiteSetting::getSection($section);
        }
        
        return WebsiteSetting::getValue($section, $key, $default);
    }
}

if (!function_exists('detectLanguageClass')) {
    function detectLanguageClass($text) {
        if (empty($text)) {
            return 'inter'; // Default to English font
        }
        
        // Bengali Unicode range: U+0980 to U+09FF
        $bengaliRegex = '/[\x{0980}-\x{09FF}]/u';
        
        if (preg_match($bengaliRegex, $text)) {
            return 'tiro';
        }
        
        return 'inter';
    }
}