<?php

return [
    'class' => 'app\components\paymentSystem\PaymentSystem',
    'public_id' => env('PUBLIC_ID'),  // Public ID из личного кабинета
    'api_secret' => env('API_SECRET')  // API Secret из личного кабинета
];

?>