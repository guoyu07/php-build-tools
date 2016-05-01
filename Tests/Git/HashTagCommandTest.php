<?php

namespace Tremend\BuildTools\Tests\Git;

class HashTagCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Return current hash that a tag is poiting to
     */
    public function testHash()
    {
        $command = $this->getMockCommand('somerandomhash');

        $this->assertEquals('somerandomhash', $command->getHash());

        $fatal = <<<HEREDOC
fatal: ambiguous argument '0.1.0': unknown revision or path not in the working tree.
Use '--' to separate paths from revisions, like this:
'git <command> [<revision>...] -- [<file>...]'
HEREDOC;

        $command = $this->getMockCommand($fatal);

        $this->assertEquals(null, $command->getHash());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockCommand($returnString)
    {
        $command = $this->getMockBuilder('Tremend\BuildTools\Model\Git\HashTagCommand')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'runCommand'
            ))
            ->getMock();

        $command->method('runCommand')
            ->willReturn($returnString);

        return $command;
    }
}