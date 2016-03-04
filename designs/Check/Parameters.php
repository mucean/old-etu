<?php
//data format
$data = [
    'first' => 'string',
    'second' => 123,
    'third' => [
        'first' => 'strig',
        'second' => 123
    ],
];

//options

$options = [
    'first' => [
        'type' => 'string',
        'equal' => 'string',
        'regexp' => '/^string$/',
        'enum' => ['string', 'integer'],
        'required' => true
    ],
    'second' => [
        'type' => 'integer',
        'equal' => 123,
        'regexp' => '/^\d{1,3}$/',
        'enum' => [123, 456]
    ],
    'third' => [
        'type' => 'array',
        'equl' => [
            'first' => 'string',
            'second' => 123,
            'third' => [
                'first' => 'string',
                'second' => 123
            ]
        ],
        'option' => [
            'first' => [
                'type' => 'string',
                'equal' => 'string',
                'regexp' => '/^string$/',
                'enum' => ['string', 'integer']
            ],
            'second' => [
                'type' => 'integer',
                'equal' => 123,
                'regexp' => '/^\d{1,3}$/',
                'enum' => [123, 456]
            ],
        ]
    ]
];
