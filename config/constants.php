<?php

return [

    // Project related constants
    'global' => [
        'site' => [
            'name' => env('APP_NAME'),
            'tinyName' => 'ML',
            'version' => '1.0', // For internal code comparison (if any)
        ],
        /* 'encryption' => [
             'secretKey' => "A78D79F544B634767D715C6BDDA1A",
         ],
         'stripe' => [
             'endpoint_secret' => 'whsec_mu6UtX43VjZr936L91Cu5W0Fs1wtEN11',
         ]*/
    ],

    // Related to web-services
    'api' => [
        'config' => [
            'allowSingleDeviceLogin' => true,
            'sendHiddenLogoutPush' => true,
            'sendSMS' => false,
            'paginate' => 20,
        ],

        'global' => [
            'formats' => [
                'date' => 'j M, Y',
                'dob' => 'Y/m/d',
                'time' => 'H:i',
                'datetime' => 'j M, Y H:i',
            ],
        ],
    ],

    // Related to backend
    // Directory Constants
    'back' => [

        'theme' => [
            'configuration' => [
                'show_navigation_messages' => false,
                'show_navigation_notifications' => false,
                'show_navigation_flags' => false,
            ],

            'modules' => [
                'date_for' => 'Y-m-j',
                'date_format' => 'j F, Y',
                'date_time_format' => 'j M Y, H:i:s',
                'datetime_format' => 'j M Y, h:i A',
                'new_date_format' => 'j M Y, h:i A',
                'time_format' => 'h:i:s A',
                'tiny_loader' => 'backend/assets/dist/img/tiny-loader.gif',
            ],
        ],
    ],
    // Related to frontend
    'front' => [
        'default' => [
            'admin' => 'default.jpg',
        ],
    ]

];
