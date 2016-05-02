<?php

namespace Tremend\BuildTools\Model\Git;

use Tivie\Command\Command;

class AbstractCommand
{
    /**
     * Command to run in order to return git tags
     *
     * @var null|Command
     */
    protected $command = null;

    /**
     * Run configured command
     *
     * @return string
     */
    protected function runCommand()
    {
        $result = $this->command->run();

        if ($result->getExitCode() != 0) {
            throw new \RuntimeException(sprintf(
                'Command failed. Exit code %d, output %s',
                $result->getExitCode(),
                $result->getStdOut()));
        }

        return $result->getStdOut();
    }
}