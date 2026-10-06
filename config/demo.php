<?php

return [
    // Nonaktifkan dengan DEMO_LOGIN_ENABLED=false setelah uji coba selesai.
    'enabled' => env('DEMO_LOGIN_ENABLED', env('APP_ENV', 'production') === 'local'),
];
