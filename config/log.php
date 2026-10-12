<?php
return [
    'default' => 'file',
    'channels' => [
        'file' => [
            'type'           => 'File',
            'path'           => runtime_path() . 'log',
            'level'          => [],
            'file_datetime'  => 'Y-m-d',
            'max_files'      => 0,
            'json'           => false,
            'apart_level'    => [],
            'realtime_write' => false,
        ],
    ],
];