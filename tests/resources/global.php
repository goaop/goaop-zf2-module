<?php

return [
    'goaop_module'    => require 'goaop_module.php',
    'goaop_aspect'    => [
        \Go\ZF2\GoAopModule\Tests\Aspect\TestAspect::class,
    ],
    'service_manager' => [
        'factories' => [
            \Go\ZF2\GoAopModule\Tests\Aspect\TestAspect::class => \Zend\ServiceManager\Factory\InvokableFactory::class,
        ],
    ],
];