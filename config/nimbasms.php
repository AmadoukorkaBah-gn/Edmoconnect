<?php

return [
    'service_id' => env('NIMBA_SERVICE_ID'),
    'secret_token' => env('NIMBA_SECRET_TOKEN'),
    'sender_name' => env('NIMBA_SENDER_NAME', 'ARQAM EMO'),
    'base_url' => 'https://api.nimbasms.com/v1',
];