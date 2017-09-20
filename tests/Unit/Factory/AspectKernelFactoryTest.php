<?php

namespace Go\ZF2\GoAopModule\Tests\Unit\Factory;

use Go\Core\AspectKernel;
use Go\ZF2\GoAopModule\Factory\AspectKernelFactory;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @package Go\ZF2\GoAopModule\Tests\Unit\Factory
 */
class AspectKernelFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itCreatesKernelOnInvoke()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $serviceLocator->get('config')
            ->willReturn(['goaop_module' => require __DIR__ . '/../../resources/goaop_module.php'])
            ->shouldBeCalled();

        $factory = new AspectKernelFactory();

        $instance = $factory($serviceLocator->reveal(), AspectKernel::class);

        $this->assertInstanceOf(
            AspectKernel::class,
            $instance,
            'factory should return an instance of ' . AspectKernel::class
        );
    }

    /**
     * @test
     */
    public function itCreatesKernelOnCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $serviceLocator->get('config')
            ->willReturn(['goaop_module' => require __DIR__ . '/../../resources/goaop_module.php'])
            ->shouldBeCalled();

        $factory = new AspectKernelFactory();

        $instance = $factory->createService($serviceLocator->reveal());

        $this->assertInstanceOf(
            AspectKernel::class,
            $instance,
            'factory should return an instance of ' . AspectKernel::class
        );
    }
}