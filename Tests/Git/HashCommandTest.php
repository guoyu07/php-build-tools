<?php

namespace Tremend\BuildTools\Tests\Git;

use Tremend\BuildTools\Model\Git\HashCommand;

class HashCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Return current hash
     */
    public function testHash()
    {
        $command = $this->getMockBuilder('Tremend\BuildTools\Model\Git\HashCommand')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'runCommand'
            ))
            ->getMock();

        $command->method('runCommand')
            ->willReturn('somerandomhash');

        $this->assertEquals('somerandomhash', $command->getHash());
    }
}