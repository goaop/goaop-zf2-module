GoAopModule
==============

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/goaop/goaop-zf2-module/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/goaop/goaop-zf2-module/?branch=master)
[![GitHub release](https://img.shields.io/github/release/goaop/goaop-zf2-module.svg)](https://github.com/goaop/goaop-zf2-module/releases/latest)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.5-8892BF.svg)](https://php.net/)
[![License](https://img.shields.io/packagist/l/goaop/goaop-zf2-module.svg)](https://packagist.org/packages/goaop/goaop-zf2-module)

The GoAopModule adds support for Aspect-Oriented Programming via Go! AOP Framework for Zend Framework 2 applications.

Overview
--------

Aspect-Oriented Paradigm allows to extend the standard Object-Oriented Paradigm with special instruments for effective solving of cross-cutting concerns in your application. This code is typically present everywhere in your application (for example, logging, caching, monitoring, etc) and there is no easy way to fix this without AOP.

AOP defines new instruments for developers, they are:

 * Joinpoint - place in your code that can be used for interception, for example, execution of single public method or accessing of single object property.
 * Pointcut is a list of joinpoints defined with a special regexp-like expression for your source code, for example, all public and protected methods in the concrete class or namespace.
 * Advice is an additional callback that will be called before, after or around concrete joinpoint. For PHP each advice is represented as a `\Closure` instance, wrapped into the interceptor object.
 * Aspect is a special class that combines pointcuts and advices together, each pointcut is defined as an annotation and each advice is a method inside this aspect.
 
 You can read more about AOP in different sources, there are good articles for Java language and they can be applied for PHP too, because it's general paradigm. 
Installation
------------

GoAopModule can be easily installed with composer. Just ask a composer to download the bundle with dependencies by running the command:

```bash
$ composer require goaop/goaop-zf2-module
```

Add the `Go\ZF2\GoAopModule` to your list of modules in the config/application.config.php `modules` array:
```php
// config/application.config.php

    // This should be an array of module namespaces used in the application.
    'modules' => array(
        'Go\ZF2\GoAopModule',
        'Application',
    ),
```
Make sure that this service provider is the **first item** in this list. This is required for the AOP engine to work correctly.

Configuration
-------------

The default configuration in the `config/module.config.php` file. Copy this file to your own config directory to modify the values. 

Configuration can be used for additional tuning of AOP kernel and source code whitelistsing/blacklisting.
```php
// config/module.config.php

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
    'debug' => false,

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
    'includePaths' => [
        $basicDirectory . '/module'
    ],

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
```

Defining new aspects
--------------------

Aspects are services in the ZF2 application and loaded into the AOP container by the integration module after putting them into the `goaop_aspect` section in the `application.config.php file`. Here is an example how to implement a logging aspect that will log information about public method invocations in the module/ directory.


Definition of aspect class with pointcut and logging advice
```php
<?php
// module/Application/src/Application/Aspect/LoggingAspect.php

namespace Application\Aspect;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Before;

/**
 * Application logging aspect
 */
class LoggingAspect implements Aspect
{
    /**
     * Writes a log info before method execution
     *
     * @param MethodInvocation $invocation
     * @Before("execution(public Application\**->*(*))")
     */
    public function beforeMethod(MethodInvocation $invocation)
    {
        echo $invocation, json_encode($invocation->getArguments());
    }
}
```

To register this application aspect in the container, create an appropriate definition for this service in the `module.config.php` file:

```
    'service_manager' => array(
        'invokables' => array(
            LoggingAspect::class => LoggingAspect::class,
        )
    ),
```

If you need to inject several dependencies into your aspect, then you can define your own factories, initializers, etc

To automatically register the aspect in the AOP kernel, add it's service name into the `goaop_aspect` config section of your Application module `module.config.php` or `application.config.php`:

```
    'goaop_aspect' => [
        LoggingAspect::class
    ]
```


License
-------

This module is under the MIT license. See the complete LICENSE in the root directory
