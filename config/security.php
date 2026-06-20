<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Login Rate Limiting
    |--------------------------------------------------------------------------
    |
    | LOGIN_MAX_ATTEMPTS  — failed attempts (per email + IP) before lockout.
    | LOGIN_DECAY_SECONDS — how many seconds the lockout lasts.
    | LOGIN_THROTTLE_PER_MINUTE — max requests per IP per minute at the
    |                             route layer (defence-in-depth).
    |
    */

    'login' => [
        'max_attempts'         => (int) env('LOGIN_MAX_ATTEMPTS', 5),
        'decay_seconds'        => (int) env('LOGIN_DECAY_SECONDS', 60),
        'throttle_per_minute'  => (int) env('LOGIN_THROTTLE_PER_MINUTE', 10),
    ],

];
