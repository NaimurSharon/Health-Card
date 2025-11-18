<?php

use App\Models\Setting;

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