<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Video package config
    |--------------------------------------------------------------------------
    |
    | `cleanup_ttl` determines how many seconds a participant may be inactive
    | before being considered stale and removed by the cleanup routine.
    |
    */

    'cleanup_ttl' => env('VIDEO_CLEANUP_TTL', 30),
];
