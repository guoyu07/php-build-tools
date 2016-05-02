<?php

namespace Tremend\BuildTools\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Tremend\BuildTools\Model\File\VersionReader;
use Tremend\BuildTools\Model\Git\CommitCommand;
use Tremend\BuildTools\Model\Git\HashCommand;
use Tremend\BuildTools\Model\Git\HashTagCommand;
use Tremend\BuildTools\Model\Git\TagCommand;
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
                'commit-message',
                null,
                InputOption::VALUE_OPTIONAL,
                'Commit message.',
                'Bumped version to %s'
            )
            ->addOption(
                'author-name',
                null,
                InputOption::VALUE_OPTIONAL,
                'Commit as author name.'
            )
            ->addOption(
                'author-email',
                null,
                InputOption::VALUE_OPTIONAL,
                'Commit as author email.'
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
        $hash = $this->getHash($input);
        $version = $this->getVersion($input);

        /**
         * No hash found
         */
        if (null === $hash) {
            throw new RuntimeException('Could not determine a valid hash for git repository path.');
        }

        /**
         * No version detected
         */
        if (empty($version)) {
            $output->writeln(sprintf('Could not determine any version stored in %s.', $input->getOption('version-filename')));
            return;
        }

        $output->writeln(sprintf('Current version is %s', $version));

        $hashByVersion = $this->getHashTag($input, $version);

        if ($hashByVersion) {
            $output->writeln(sprintf('Current version is pointing to %s', $hashByVersion));
        }
        else {
            $output->writeln(sprintf('Current version not pointing to any hash. This is the first version.'));

            if (!$input->getOption('dry-run')) $this->tag($input, $version);

            return;
        }

        /**
         * If hashes are the same, exit
         */
        if ($hash === $hashByVersion) {
            $output->writeln('No changes committed since last version. The script does nothing.');
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

        $this->commit($input, $version);

        $this->tag($input, $version);
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
     * Return hash pointed by version
     *
     * @param InputInterface $input
     * @param $tag
     * @return mixed
     */
    protected function getHashTag(InputInterface $input, $tag)
    {
        $hashCommand = new HashTagCommand($input->getArgument('git-path'), $tag);
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

    /**
     * Tag hash with current version
     *
     * @param InputInterface $input
     * @param $version
     */
    protected function tag(InputInterface $input, $version)
    {
        $tagCommand = new TagCommand($input->getArgument('git-path'), $version);
        $tagCommand->tag();
    }

    /**
     * Commit version file
     *
     * @param InputInterface $input
     */
    protected function commit(InputInterface $input, $version)
    {
        $message = sprintf($input->getOption('commit-message'), $version);

        $commitCommand = new CommitCommand(
            $input->getArgument('git-path'),
            $message,
            $input->getOption('version-filename'),
            $input->getOption('author-name'),
            $input->getOption('author-email'));
        $commitCommand->commit();
    }
}