<?php
/*
 * Go! AOP framework
 *
 * @copyright Copyright 2016, Lisachenko Alexander <lisachenko.it@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Go\ZF2\GoAopModule;

use Go\Core\AspectContainer;
use Zend\EventManager\StaticEventManager;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;

/**
 * ZF2 Module for registration of Go! AOP Framework
 */
class Module implements ConfigProviderInterface
{
    public function __construct()
    {
        $manager = StaticEventManager::getInstance();
        // Register our listener with max priority
        $manager->attach(Application::class, MvcEvent::EVENT_BOOTSTRAP, [$this, 'onBootstrap'], PHP_INT_MAX);
    }

    /**
     * Listen to the bootstrap event
     *
     * @param MvcEvent $e
     *
     * @return array
     */
    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        /** @var AspectContainer $aspectContainer */
        $aspectContainer = $serviceManager->get(AspectContainer::class);
        $config          = $serviceManager->get('config');
        $listOfAspects   = $config['goaop_aspect'];
        foreach ($listOfAspects as $aspectService) {
            $aspect = $serviceManager->get($aspectService);
            $aspectContainer->registerAspect($aspect);
        }
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
