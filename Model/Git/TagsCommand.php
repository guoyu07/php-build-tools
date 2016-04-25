<?php

namespace Tremend\BuildTools\Model\Git;

use Tivie\Command\Argument;
use Tivie\Command\Command;

/**
 * Class Tags
 *
 * List git tags
 *
 * @package Tremend\BuildTools\Model
 */
class TagsCommand
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
     * Return current tags for the git repository
     *
     * @return Tag[]
     */
    public function getTags()
    {
        $result = $this->command->run();
        $out = $result->getStdOut();
        $raw = explode(PHP_EOL, $out);

        $tags = array();
        foreach ($raw as $data) {
            if (stripos($data, ' ') === false) break;

            list($hash, $refTag) = explode(' ', $data);
            $path = explode('/', $refTag);

            $tag = new Tag($path[2], $hash);
            $tags[$hash] = $tag;
        }

        return $tags;
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
            ->addArgument(new Argument('show-ref'))
            ->addArgument(new Argument('--tags'));
        return $command;
    }
}