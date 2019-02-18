<?php

return [
    'class' => 'app\components\orangedataclient\orangedata_client',
    'api_url' => 2443,
    'sign_pkey' => getcwd() . '\file_for_test\private_key.pem',
    'ssl_client_key' => getcwd() . '\file_for_test\client.key',
    'ssl_client_crt' => getcwd() . '\file_for_test\client.crt',
    'ssl_ca_cert' => getcwd() . '\file_for_test\cacert.pem',
    'ssl_client_crt_pass' => getcwd() . '1234',
    'inn' => '0123456789',
];

?>