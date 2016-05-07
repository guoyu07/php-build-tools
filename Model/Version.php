<?php

namespace Tremend\BuildTools\Model;

class Version
{
    private $major;

    private $minor;

    private $patch;

    /**
     * Version constructor.
     *
     * Will parse a version into its counterparts
     * Eg: 0.1.2
     * Major: 0
     * Minor: 1
     * Patch: 2
     *
     * @param $version string
     */
    public function __construct($version)
    {
        $parts = $this->parse($version);
        list($this->major, $this->minor, $this->patch) = $parts;
    }

    /**
     * Return version string as major.minor.patch
     *
     * @return string
     */
    public function getVersion()
    {
        return implode('.', array(
            $this->major,
            $this->minor,
            $this->patch
        ));
    }

    /**
     * Increment patch part by step, default 1
     *
     * @param int $step
     */
    public function incrementPath($step = 1)
    {
        $this->patch += $step;
    }

    /**
     * Parse version number
     *
     * @param $version
     * @return array
     */
    protected function parse($version)
    {
        $parts = explode('.', $version);

        if (!preg_match("/\\d+\\.\\d+\\.\\d+/i", $version) || count($parts) !== 3) {
            throw new \RuntimeException(sprintf('Could not parse version string %s as major.minor.patch', $version));
        }

        return $parts;
    }
}