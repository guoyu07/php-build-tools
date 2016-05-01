<?php

namespace Tremend\BuildTools\Model\Git;

use Tivie\Command\Argument;
use Tivie\Command\Command;

class HashCommand
{
    /**
     * Full path to git repository
     *
     * @var null
     */
    private $gitDir = null;

    /**
     * Command to run in order to return git tags
     *
     * @var null|Command
     */
    private $command = null;

    /**
     * Tags constructor.
     * @param $gitDir
     */
    public function __construct($gitDir)
    {
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
            ->chdir(realpath($this->gitDir))
            ->setCommand('git')
            ->addArgument(new Argument('rev-parse'))
            ->addArgument(new Argument('HEAD'));
        return $command;
    }

    /**
     * Run configured command
     *
     * @return string
     */
    protected function runCommand()
    {
        $result = $this->command->run();
        return $result->getStdOut();
    }
}