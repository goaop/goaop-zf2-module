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
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManagerInterface;

/**
 * ZF2 Module for registration of Go! AOP Framework
 */
class Module implements ConfigProviderInterface, InitProviderInterface
{
    /**
     * @inheritDoc
     */
    public function init(ModuleManagerInterface $manager)
    {
        $manager->getEventManager()->attach(
            ModuleEvent::EVENT_LOAD_MODULES_POST,
            [ $this, 'initializeAspects' ]
        );
    }

    /**
     * Register aspects after all modules are loaded.
     *
     * @param ModuleEvent $e
     */
    public function initializeAspects(ModuleEvent $e)
    {
        $serviceManager = $e->getParam('ServiceManager');

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
