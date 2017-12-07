<?php

return [
    \Go\Zend\Framework\Module::CONFIG_KEY => require 'goaop_module.php',

    \Go\Zend\Framework\Module::ASPECT_CONFIG_KEY => [
        \Go\Zend\Framework\Tests\Aspect\TestAspect::class,
    ],

    'service_manager' => [
        'factories' => [
            \Go\Zend\Framework\Tests\Aspect\TestAspect::class => \Zend\ServiceManager\Factory\InvokableFactory::class,
        ],
    ],
];