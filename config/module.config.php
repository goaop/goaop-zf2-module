<?php

/*
 |--------------------------------------------------------------------------
 | Go! AOP Configuration
 |--------------------------------------------------------------------------
 |
 */
use Go\Core\AspectContainer;
use Go\Core\AspectKernel;
use Go\Core\GoAspectContainer;
use Go\Zend\Framework\Factory\AspectContainerFactory;
use Go\Zend\Framework\Factory\AspectKernelFactory;

$basicDirectory = defined('APPLICATION_PATH') ? APPLICATION_PATH : __DIR__ . '/../../../..';

$moduleConfig = [
    /*
     |--------------------------------------------------------------------------
     | AOP Debug Mode
     |--------------------------------------------------------------------------
     |
     | When AOP is in debug mode, then breakpoints in the original source code
     | will work. Also engine will refresh cache files if the original files were
     | changed.
     | For production mode, no extra filemtime checks and better integration with opcache
     |
     */
    'debug' => true,

    /*
     |--------------------------------------------------------------------------
     | Application root directory
     |--------------------------------------------------------------------------
     |
     | AOP will be applied only to the files in this directory, change it if needed
     */
    'appDir' => $basicDirectory,

    /*
     |--------------------------------------------------------------------------
     | AOP cache directory
     |--------------------------------------------------------------------------
     |
     | AOP engine will put all transformed files and caches in that directory
     */
    'cacheDir' => $basicDirectory . '/data/cache/aspect',

    /*
     |--------------------------------------------------------------------------
     | Cache file mode
     |--------------------------------------------------------------------------
     |
     | If configured then will be used as cache file mode for chmod
     */
    'cacheFileMode' => 0770 & ~umask(),

    /*
     |--------------------------------------------------------------------------
     | Controls miscellaneous features of AOP engine
     |--------------------------------------------------------------------------
     |
     | See \Go\Aop\Features enumeration for bit mask
     */
    'features' => 0,

    /*
     |--------------------------------------------------------------------------
     | White list of directories
     |--------------------------------------------------------------------------
     |
     | AOP will check this list to apply an AOP to selected directories only,
     | leave it empty if you want AOP to be applied to all files in the appDir
     */
    'includePaths' => [],

    /*
     |--------------------------------------------------------------------------
     | Black list of directories
     |--------------------------------------------------------------------------
     |
     | AOP will check this list to disable AOP for selected directories
     */
    'excludePaths' => [],

    /**
     |--------------------------------------------------------------------------
     | AOP container class
     |--------------------------------------------------------------------------
     |
     | This option can be useful for extension and fine-tuning of services
     */
    'containerClass' => GoAspectContainer::class,
];

return [
    \Go\Zend\Framework\Module::CONFIG_KEY => $moduleConfig,

    'service_manager' => [
        'factories' => [
            AspectKernel::class    => AspectKernelFactory::class,
            AspectContainer::class => AspectContainerFactory::class,
        ]
    ],

    \Go\Zend\Framework\Module::ASPECT_CONFIG_KEY => []
];
