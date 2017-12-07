<?php

namespace Go\Zend\Framework\Tests\Functional;

use Go\Zend\Framework\Console\Command\WarmupCommand;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package Go\Zend\Framework\Tests\Functional
 */
class WarmupTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldFindAdviceAccordingToConfig()
    {
        $input = $this->prophesize(InputInterface::class);
        $input->bind(Argument::any())->willReturn();
        $input->isInteractive()->willReturn(false);
        $input->hasArgument(Argument::any())->willReturn(false);
        $input->validate()->willReturn();

        $input->getArgument('applicationConfig')
            ->willReturn(__DIR__ . '/../resources/application_config.php');

        $output = $this->getMockBuilder(OutputInterface::class)
            ->setMethods(['writeln'])
            ->getMockForAbstractClass();

        $output->expects($this->at(1))
            ->method('writeln')
            ->with('Total <info>1</info> files to process.');

        $command = new WarmupCommand();
        $command->run($input->reveal(), $output);
    }
}