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
                'getHashTag',
                'getHash',
                'getVersion',
                'setVersion',
                'commit',
                'tag'
            ))
            ->getMock();

        $command->method('getHashTag')
            ->willReturn('123456');

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

        $command->expects($this->once())
            ->method('commit')
            ->with($input, '1.0.3');

        $command->expects($this->once())
            ->method('tag')
            ->with($input, '1.0.3');

        $output = $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'writeln'
            ))
            ->getMock();

        $execute->invokeArgs($command, array($input, $output));
    }


    public function testNoIncrement()
    {
//        /** @var VersionCommand $command */
        $command = $this->getMockBuilder('Tremend\BuildTools\Command\VersionCommand')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getHashTag',
                'getHash',
                'getVersion',
                'setVersion'
            ))
            ->getMock();

        $command->method('getHashTag')
            ->willReturn('12345');

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

        $output = $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'writeln'
            ))
            ->getMock();

        $output->expects($this->atLeastOnce())
            ->method('writeln')
            ->withConsecutive(
                array('Current version is 1.0.2'),
                array('Current version is pointing to 12345'),
                array('No changes committed since last version. The script does nothing.')
            );

        $execute->invokeArgs($command, array($input, $output));
    }

    public function testFirstVersion()
    {
        /** @var VersionCommand $command */
        $command = $this->getMockBuilder('Tremend\BuildTools\Command\VersionCommand')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getHashTag',
                'getHash',
                'getVersion',
                'setVersion',
                'tag'
            ))
            ->getMock();

        $command->method('getHashTag')
            ->willReturn(null);

        $command->method('getHash')
            ->willReturn('12345');

        $command->method('getVersion')
            ->willReturn('0.1.0');

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
            ->method('tag')
            ->with($input, '0.1.0');

        $output = $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'writeln'
            ))
            ->getMock();

        $output->expects($this->atLeastOnce())
            ->method('writeln')
            ->withConsecutive(
                array('Current version is 0.1.0'),
                array('Current version not pointing to any hash. This is the first version.')
            );

        $execute->invokeArgs($command, array($input, $output));
    }
}