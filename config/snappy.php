<?php

return array(
    'pdf' => array(
        'enabled' => true,
        'binary' => base_path('vendor/h4cc/wkhtmltopdf-i386/bin/wkhtmltopdf-i386'),
        // 'binary' => '/usr/local/bin/wkhtmltopdf',
        'timeout' => false,
        'options' => array(),
    )
);