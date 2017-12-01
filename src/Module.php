<?php
/*
 * Go! AOP framework
 *
 * @copyright Copyright 2016, Lisachenko Alexander <lisachenko.it@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Go\Zend\Framework;

use Go\Core\AspectContainer;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManagerInterface;

/**
 * Module for registration of Go! AOP Framework
 */
class Module implements ConfigProviderInterface, InitProviderInterface
{
    public const CONFIG_KEY = 'goaop_module';
    public const ASPECT_CONFIG_KEY = 'goaop_aspect';

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
        $listOfAspects   = $config[self::ASPECT_CONFIG_KEY];

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
