<?php

namespace Tremend\BuildTools\Tests\File;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use org\bovigo\vfs\vfsStreamWrapper;
use Tremend\BuildTools\Model\File\VersionReader;

class VersionReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var vfsStreamFile
     */
    private $validFile;

    /**
     * @var vfsStreamFile
     */
    private $invalidFile;

    public function setUp()
    {
        parent::setUp();

        vfsStreamWrapper::register();
        $root = vfsStreamWrapper::setRoot(new vfsStreamDirectory('root'));
        $this->validFile = vfsStream::newFile('VERSION')
            ->withContent('VERSION=1.2.0')
            ->at($root);

        $this->invalidFile = vfsStream::newFile('VERSION_WRONG')
            ->withContent('VERSION=wrong.content')
            ->at($root);
    }

    public function testReader()
    {
        $reader = new VersionReader($this->validFile->url());

        $this->assertEquals('1.2.0', $reader->getVersion());

        $reader->setVersion('2.3.4');
        $this->assertEquals('VERSION=2.3.4', $this->validFile->getContent());
    }

    public function testCouldNotReadFile()
    {
        $reader = new VersionReader('not_readable');

        $this->setExpectedException('\RuntimeException', 'Could not read file not_readable');
        $reader->getVersion();
    }

    public function testInvalidVersionString()
    {
        $reader = new VersionReader($this->invalidFile->url());

        $this->setExpectedException('\RuntimeException', 'Invalid version string, expected VERSION=major.minor.patch');
        $reader->getVersion();
    }

    public function testWrongVersionString()
    {
        $reader = new VersionReader($this->invalidFile->url());

        $this->setExpectedException('\RuntimeException', 'Invalid version string, expected VERSION=major.minor.patch');
        $reader->setVersion('wrong');
    }

}