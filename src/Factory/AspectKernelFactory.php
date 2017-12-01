<?php
/*
 * Go! AOP framework
 *
 * @copyright Copyright 2016, Lisachenko Alexander <lisachenko.it@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Go\Zend\Framework\Factory;

use Go\Zend\Framework\Kernel\AspectKernel;
use Go\Zend\Framework\Module;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AspectKernelFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * This method gains ZF3 compatibility
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array              $options
     *
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return $this->createService($container);
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $aspectKernel = AspectKernel::getInstance();
        $aspectKernel->init($serviceLocator->get('config')[Module::CONFIG_KEY]);

        return $aspectKernel;
    }
}
