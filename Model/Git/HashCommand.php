<?php

namespace Tremend\BuildTools\Model\Git;

use Tivie\Command\Argument;
use Tivie\Command\Command;

class HashCommand extends AbstractCommand
{
    /**
     * Full path to git repository
     *
     * @var null
     */
    private $repoDir = null;

    /**
     * Git executable dir
     *
     * @var null
     */
    private $gitDir = null;

    /**
     * Tags constructor.
     * @param $gitDir
     */
    public function __construct($repoDir, $gitDir)
    {
        $this->repoDir = $repoDir;
        $this->gitDir = $gitDir;

        $this->command = $this->buildCommand();
    }

    /**
     * @return mixed
     */
    public function getHash()
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
            ->chdir(realpath($this->repoDir))
            ->setCommand($this->gitDir)
            ->addArgument(new Argument('rev-parse'))
            ->addArgument(new Argument('HEAD'));

        return $command;
    }
}