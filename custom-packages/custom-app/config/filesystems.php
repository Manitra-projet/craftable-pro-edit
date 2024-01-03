<?php

return [
    'disks' => [
        'uploads' => [
            'driver' => 'local',
            'root'   => storage_path('uploads'),
        ],

        'media' => [
            'driver' => 'local',
            'root'   => public_path('media'),
            'url'    => env('APP_URL') . '/media',
        ],
    ],
];
