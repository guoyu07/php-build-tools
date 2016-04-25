#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Tremend\BuildTools\Command\VersionCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new VersionCommand());
$application->run();