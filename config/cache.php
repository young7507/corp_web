<?php
return [
    'default' => 'file',
    'stores'  => [
        'file' => [
            'type'     => 'File',
            'path'     => runtime_path() . 'cache',
            'expire'   => 0,
            'prefix'   => '',
            'tag_multi'=> true,
        ],
    ],
];