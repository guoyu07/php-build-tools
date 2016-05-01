<?php

namespace Tremend\BuildTools\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Tremend\BuildTools\Model\File\VersionReader;
use Tremend\BuildTools\Model\Git\HashCommand;
use Tremend\BuildTools\Model\Git\TagsCommand;
use Tremend\BuildTools\Model\Version;

class VersionCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('version:increment')
            ->setDescription('Increment the PATCH part of a version string MAJOR.MINOR.PATCH and write the result to file.')
            ->addArgument(
                'git-path',
                InputArgument::REQUIRED,
                'Full path to git repository'
            )
            ->addOption(
                'version-filename',
                null,
                InputOption::VALUE_OPTIONAL,
                'File where the version string should be stored.',
                'VERSION'
            )
            ->addOption(
                'dry-run',
                null,
                InputOption::VALUE_OPTIONAL,
                'If the action will only be displayed on the output. No real modification will be made to the file',
                false
            );
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tags = $this->getTags($input);
        $hash = $this->getHash($input);
        $version = $this->getVersion($input);

        $output->writeln(sprintf('Current version is %s', $version));

        if (null === $hash) {
            throw new RuntimeException('Could not determine a valid hash for git repository path.');
        }

        /**
         * No tags available
         */
        if (empty($tags)) {
            $output->writeln('No tags has been created so far. Version will not be incremented.');
            return;
        }

        /**
         * Hash has a tag associated with it
         */
        if (isset($tags[$hash])) {
            $output->writeln(sprintf('Current hash %s has already a tag %s associated with it. Version will not be incremented.', $hash, $tags[$hash]->getTag()));
            return;
        }

        /**
         * Increment version
         */
        $model = new Version($version);
        $model->incrementPath();

        $version = $model->getVersion();
        $output->writeln(sprintf('Version has been incremented to %s', $version));

        if ($input->getOption('dry-run')) {
            $output->writeln('Changes not written to file.');
            return;
        }

        /**
         * Write changes to file
         */
        $this->setVersion($input, $version);
    }

    /**
     * Return git tags
     *
     * @param $input
     * @return array
     */
    protected function getTags(InputInterface $input)
    {
        $tagsCommand = new TagsCommand($input->getArgument('git-path'));
        $tags = $tagsCommand->getTags();

        return $tags;
    }

    /**
     * Return git hash
     *
     * @param InputInterface $input
     * @return mixed
     */
    protected function getHash(InputInterface $input)
    {
        $hashCommand = new HashCommand($input->getArgument('git-path'));
        $hash = $hashCommand->getHash();

        return $hash;
    }

    /**
     * Return version file as major.minor.patch
     *
     * @param InputInterface $input
     * @return null|string
     */
    protected function getVersion(InputInterface $input)
    {
        $versionReader = new VersionReader($input->getOption('version-filename'));
        return $versionReader->getVersion();
    }

    /**
     * Write new version to file
     *
     * @param InputInterface $input
     * @param $version
     */
    protected function setVersion(InputInterface $input, $version)
    {
        $versionReader = new VersionReader($input->getOption('version-filename'));

        $versionReader->setVersion($version);
    }
}