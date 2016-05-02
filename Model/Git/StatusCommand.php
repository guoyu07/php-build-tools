<?php

namespace Tremend\BuildTools\Model\Git;

use Tivie\Command\Argument;
use Tivie\Command\Command;

class StatusCommand extends AbstractCommand
{
    /**
     * Full path to git repository
     *
     * @var null
     */
    private $gitDir = null;

    /**
     * Check specific file for version
     * @var null
     */
    private $specificFile = null;

    /**
     * Tags constructor.
     * @param $gitDir
     * @param $specificFile
     */
    public function __construct($gitDir, $specificFile = null)
    {
        $this->gitDir = $gitDir;
        $this->specificFile = $specificFile;
        $this->command = $this->buildCommand();
    }

    /**
     * @return mixed
     */
    public function getStatus()
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
            ->addArgument(new Argument('status'))
            ->addArgument(new Argument('-s'));

        if (null !== $this->specificFile) {
            $command->addArgument(new Argument($this->specificFile));
        }

        return $command;
    }
}