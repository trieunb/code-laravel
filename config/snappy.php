<?php

return [
    'pdf' => [
        'enabled' => true,
        'binary' => env('WKHTMLTOPDF_BIN_PATH', '/usr/bin/wkhtmltopdf'),
        'timeout' => false,
        'options' => [],
    ],
];
