<?php
return [
    'default' => 'file',
    'stores'  => [
        'file' => [
            'type'   => 'File',
            'path'   => runtime_path() . 'session',
            'expire' => 1440,
            'prefix' => '',
        ],
    ],
];