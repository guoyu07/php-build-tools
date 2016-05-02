<?php

namespace Tremend\BuildTools\Model\Git;

use Tivie\Command\Argument;
use Tivie\Command\Command;

class HashTagCommand extends AbstractCommand
{
    /**
     * Path to git repository
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
     * Tag to return hash pointing to
     *
     * @var string
     */
    private $tag;

    /**
     * Tags constructor.
     * @param $gitDir
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
            ->addArgument(new Argument('rev-list'))
            ->addArgument(new Argument('-1'))
            ->addArgument(new Argument($this->tag));
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

        if ($result->getExitCode() != 0) {
            $error = $result->getStdErr();

            /**
             * Tag not found in the working tree
             */
            if (strpos($error, 'fatal') !== false) {
                return '';
            }

            throw new \RuntimeException(sprintf(
                'Command failed. Exit code %d, output %s',
                $result->getExitCode(),
                $result->getStdOut()));
        }

        return $result->getStdOut();
    }
}