<?php
return [
    '/admin' => [
        'bath_path' => '/Admin',
        'rewrite' => []
    ],
    '/api' => [
        'bath_path' => '/Controller/Api',
        'rewirte' => []
    ],
    '/' => [
        'bath_path' => '/Controller',
        'rewrite' => [
            '#^/user/(\d+)/follow' => '/user/follow'
        ]
    ]
];
