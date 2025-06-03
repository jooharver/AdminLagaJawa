<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY'), // pakai env
    'client_key' => env('MIDTRANS_CLIENT_KEY'), // pakai env
    'is_production' => env('MIDTRANS_PRODUCTION', false),
    'sanitized' => true,
    '3ds' => true,
];

