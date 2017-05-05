<?php
/*
 * Go! AOP framework
 *
 * @copyright Copyright 2016, Lisachenko Alexander <lisachenko.it@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Go\ZF2\GoAopModule\Factory;

use Go\ZF2\GoAopModule\Kernel\AspectZf2Kernel;
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
        $aspectKernel = AspectZf2Kernel::getInstance();
        $aspectKernel->init($serviceLocator->get('config')['goaop_module']);

        return $aspectKernel;
    }
}
