<?php

$modules = [
    'Go\ZF2\GoAopModule',
];

if (class_exists('Zend\Router\Module')) {
    $modules[] = 'Zend\Router';
}

return [
    'modules' => $modules,
    'module_listener_options' => [
        'module_paths' => [
            __DIR__ . '/../../vendor',
        ],
        'config_glob_paths' => [
            __DIR__ . '/{{,*.}global,{,*.}local}.php',
        ],
    ],
];
