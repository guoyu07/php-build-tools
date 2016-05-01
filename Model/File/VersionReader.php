<?php

namespace Tremend\BuildTools\Model\File;

/**
 * Class VersionReader
 * @package Tremend\BuildTools\Model\File
 */
class VersionReader
{
    /**
     * Filepath to file version
     *
     * @var string
     */
    private $filepath;

    /**
     * VersionReader constructor.
     * @param $filepath
     */
    public function __construct($filepath)
    {
        $this->filepath = $filepath;
    }

    /**
     * Return version string from file as major.minor.patch
     *
     * @return null|string
     */
    public function getVersion()
    {
        if (!file_exists($this->filepath)) {
            throw new \RuntimeException(sprintf('Could not read file %s', $this->filepath));
        }

        $content = file_get_contents($this->filepath);

        if (!preg_match("/(VERSION=|)(\\d+\\.\\d+\\.\\d)/i", $content, $matches)) {
            throw new \RuntimeException(sprintf('Invalid version string, expected VERSION=major.minor.patch'));
        }

        return $matches[2];
    }

    /**
     * Write version to file
     *
     * @param $version
     * @return bool|int
     */
    public function setVersion($version)
    {
        if (!preg_match("/\\d+\\.\\d+\\.\\d/i", $version)) {
            throw new \RuntimeException(sprintf('Invalid version string, expected VERSION=major.minor.patch'));
        }

        $string = "VERSION=" . $version;

        return file_put_contents($this->filepath, $string);
    }
}