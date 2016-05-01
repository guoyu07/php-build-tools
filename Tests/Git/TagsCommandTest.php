<?php

namespace Tremend\BuildTools\Tests;

use Tremend\BuildTools\Model\Git\Tag;

class TagsCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test there are no tags
     */
    public function testNoTags()
    {
        $command = $this->getMockCommand('');

        $this->assertEquals(array(), $command->getTags());
    }

    public function testSomeTags()
    {
        $return = <<<HEREDOC
ff17437abd60734c25088a77a5655e555c0c3074 refs/tags/0.1.0
HEREDOC;

        $command = $this->getMockCommand($return);

        $expects = array(
            'ff17437abd60734c25088a77a5655e555c0c3074' => new Tag('0.1.0', 'ff17437abd60734c25088a77a5655e555c0c3074')
        );

        $this->assertEquals($expects, $command->getTags());
    }

    /**
     * Return mock command with defined return string from running command
     */
    protected function getMockCommand($returnString)
    {
        $command = $this->getMockBuilder('Tremend\BuildTools\Model\Git\TagsCommand')
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