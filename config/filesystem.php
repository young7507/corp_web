<?php
return [
    'default' => 'local',
    'disks'   => [
        'local' => [
            'type' => 'local',
            'root' => app()->getRootPath() . 'runtime/storage',
        ],
        'public' => [
            'type'      => 'local',
            'root'      => app()->getRootPath() . 'public/storage',
            'url'       => '/storage',
            'visibility'=> 'public',
        ],
    ],
];