<?php

namespace Tremend\BuildTools\Model\Git;

use Tivie\Command\Argument;
use Tivie\Command\Command;

/**
 * Class CommitCommand
 * @package Tremend\BuildTools\Model\Git
 */
class CommitCommand extends AbstractCommand
{
    /**
     * Full path to git repository
     *
     * @var null
     */
    private $gitDir = null;

    /**
     * Commit specific file
     * @var null
     */
    private $pathFile = null;

    /**
     * Commit message
     *
     * @var null
     */
    private $message = null;

    /**
     * Commit message as author name and email
     *
     * @var null
     */
    private $author = null;

    /**
     * Commit constructor specify git message and path to the file to commit
     *
     * @param $gitDir
     * @param $message
     * @param $pathFile
     * @param $authorName
     * @param $authorEmail
     */
    public function __construct($gitDir, $message, $pathFile = null, $authorName = null, $authorEmail = null)
    {
        $this->gitDir = $gitDir;
        $this->pathFile = $pathFile;
        $this->message = $message;

        if (null !== $authorName && null == $authorEmail) {
            $this->author = $authorName . ' <>';
        }
        if (null !== $authorEmail && null == $authorName) {
            $this->author = $authorEmail . ' <>';
        }
        if (null !== $authorName && null !== $authorEmail) {
            $this->author = $authorName . ' <' . $authorEmail . '>';
        }

        $this->command = $this->buildCommand();
    }

    /**
     * @return mixed
     */
    public function commit()
    {
        $out = $this->runCommand();

        if (empty($out)) {
            return null;
        }

        return $out;
    }

    /**
     * Build commit command
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
            ->addArgument(new Argument('commit'))
            ->addArgument(new Argument('-m'))
            ->addArgument(new Argument($this->message));

        if (null != $this->author) {
            $command
                ->addArgument(new Argument('--author', $this->author));
        }

        if (null !== $this->pathFile) {
            $command->addArgument(new Argument('--include', $this->pathFile));
        }

        return $command;
    }
}