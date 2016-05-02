<?php

namespace Tremend\BuildTools\Model\Git;

use Tivie\Command\Argument;
use Tivie\Command\Command;

class TagCommand extends AbstractCommand
{
    /**
     * Full path to git repository
     *
     * @var null
     */
    private $gitDir = null;

    /**
     * Tag to tag current hash
     *
     * @var null
     */
    private $tag = null;

    /**
     * Tag constructor.
     *
     * @param $gitDir
     * @param $tag
     */
    public function __construct($gitDir, $tag)
    {
        $this->gitDir = $gitDir;
        $this->tag = $tag;
        $this->command = $this->buildCommand();
    }

    /**
     * @return mixed
     */
    public function tag()
    {
        $out = $this->runCommand();

        if (empty($out)) {
            return null;
        }

        return $out;
    }

    /**
     * List git available tags
     *
     * @return Command
     * @throws \Tivie\Command\Exception\Exception
     * @throws \Tivie\Command\Exception\InvalidArgumentException
     */
    protected function buildCommand()
    {
        $command = new Command(\Tivie\Command\DONT_ADD_SPACE_BEFORE_VALUE);
        $command
            ->chdir(realpath($this->gitDir))
            ->setCommand('git')
            ->addArgument(new Argument('tag'))
            ->addArgument(new Argument($this->tag));

        return $command;
    }
}