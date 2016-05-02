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
    private $repoDir = null;

    /**
     * Git executable dir
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
     * @param $repoDir
     * @param $gitDir
     * @param $tag
     */
    public function __construct($repoDir, $gitDir, $tag)
    {
        $this->repoDir = $repoDir;
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
        $command = new Command(\Tivie\Command\ESCAPE);
        $command
            ->chdir(realpath($this->repoDir))
            ->setCommand($this->gitDir)
            ->addArgument(new Argument('tag', null, null, false))
            ->addArgument(new Argument($this->tag, null, null, true));

        return $command;
    }
}