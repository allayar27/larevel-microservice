<?php

return [
    'secret' => env('JWT_SECRET'),
    'expire_minutes' => env('JWT_EXPIRE_MINUTES', 60),
    'algorithm' => env('JWT_ALGORITHM', 'HS256'),
];
