<?php

namespace Go\ZF2\GoAopModule\Tests\Unit\Factory;

use Go\Core\AspectContainer;
use Go\Core\AspectKernel;
use Go\ZF2\GoAopModule\Factory\AspectContainerFactory;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @package Go\ZF2\GoAopModule\Tests\Unit\Factory
 */
class AspectContainerFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itCreatesAspectContainerOnInvoke()
    {
        $aspectContainer = $this->prophesize(AspectContainer::class);

        $aspectKernel = $this->prophesize(AspectKernel::class);
        $aspectKernel->getContainer()
            ->willReturn($aspectContainer->reveal())
            ->shouldBeCalled();

        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $serviceLocator->get(AspectKernel::class)
            ->willReturn($aspectKernel->reveal())
            ->shouldBeCalled();

        $factory = new AspectContainerFactory();

        $instance = $factory($serviceLocator->reveal(), AspectContainer::class);

        $this->assertInstanceOf(
            AspectContainer::class,
            $instance,
            'factory should return an instance of ' . AspectContainer::class
        );
    }

    /**
     * @test
     */
    public function itCreatesAspectContainerOnCreateService()
    {
        $aspectContainer = $this->prophesize(AspectContainer::class);

        $aspectKernel = $this->prophesize(AspectKernel::class);
        $aspectKernel->getContainer()
            ->willReturn($aspectContainer->reveal())
            ->shouldBeCalled();

        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $serviceLocator->get(AspectKernel::class)
            ->willReturn($aspectKernel->reveal())
            ->shouldBeCalled();

        $factory = new AspectContainerFactory();

        $instance = $factory->createService($serviceLocator->reveal());

        $this->assertInstanceOf(
            AspectContainer::class,
            $instance,
            'factory should return an instance of ' . AspectContainer::class
        );
    }
}