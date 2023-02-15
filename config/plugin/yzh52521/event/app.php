<?php

use app\listener\TestListener;
use app\subscribe\TestSubscribe;

return [
    'enable' => true,
    'events'  => [
        'listener'  => [
            'test' => [
                TestListener::class,
            ],
        ],
        'subscribe' => [
            TestSubscribe::class,
        ],
    ]
];
