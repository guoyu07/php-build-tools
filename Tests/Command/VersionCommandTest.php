<?php

namespace Tremend\BuildTools\Tests\Command;

use Tremend\BuildTools\Command\VersionCommand;
use Tremend\BuildTools\Model\Git\Tag;

class VersionCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
//        /** @var VersionCommand $command */
        $command = $this->getMockBuilder('Tremend\BuildTools\Command\VersionCommand')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getTags',
                'getHash',
                'getVersion',
                'setVersion'
            ))
            ->getMock();

        $command->method('getTags')
            ->willReturn(array(
                '1234' => new Tag('1.0.1', '1234')
            ));

        $command->method('getHash')
            ->willReturn('12345');

        $command->method('getVersion')
            ->willReturn('1.0.2');

        $class = new \ReflectionClass('Tremend\BuildTools\Command\VersionCommand');
        $execute = $class->getMethod('execute');
        $execute->setAccessible(true);

        $input = $this->getMockBuilder('Symfony\Component\Console\Input\ArrayInput')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getArgument',
                'getOption'
            ))
            ->getMock();

        $command->expects($this->once())
            ->method('setVersion')
            ->with($input, '1.0.3');

        $output = $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'writeln'
            ))
            ->getMock();



        $execute->invokeArgs($command, array($input, $output));
    }
}