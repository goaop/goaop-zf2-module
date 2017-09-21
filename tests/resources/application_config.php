<?php

return [
    'modules' => [
        'Go\ZF2\GoAopModule',

        // Required to initialize application
        'Zend\Router',
    ],

    'module_listener_options' => [
        'module_paths' => [
            __DIR__ . '/../../vendor',
        ],
        'config_glob_paths' => [
            __DIR__ . '/{{,*.}global,{,*.}local}.php',
        ],
    ],
];
