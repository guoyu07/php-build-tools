<?php

namespace Tremend\BuildTools\Tests\Model;

use Tremend\BuildTools\Model\Version;

class VersionTest extends \PHPUnit_Framework_TestCase
{
    public function testVersion()
    {
        $version = new Version('1.0.2');

        $this->assertEquals('1.0.2', $version->getVersion());

        $version->incrementPath(1);
        $this->assertEquals('1.0.3', $version->getVersion());

        $version->incrementPath(2);
        $this->assertEquals('1.0.5', $version->getVersion());

        $this->setExpectedException(
            '\RuntimeException',
            'Could not parse version string wrong.version as major.minor.patch'
        );
        new Version('wrong.version');
    }
}