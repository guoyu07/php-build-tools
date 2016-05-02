<?php

namespace Tremend\BuildTools\Model\Git;

use Tivie\Command\Argument;
use Tivie\Command\Command;

class HashTagCommand extends AbstractCommand
{
    /**
     * Full path to git repository
     *
     * @var null
     */
    private $gitDir = null;

    /**
     * Tag to return hash pointing to
     *
     * @var string
     */
    private $tag;

    /**
     * Tags constructor.
     * @param $gitDir
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
    public function getHash()
    {
        $out = $this->runCommand();

        if (empty($out) || strpos($out, 'fatal') !== false) {
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
            ->addArgument(new Argument('rev-list'))
            ->addArgument(new Argument('-1'))
            ->addArgument(new Argument($this->tag));
        return $command;
    }
}