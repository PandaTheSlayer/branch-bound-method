#!/usr/bin/env php
<?php

use Panda\Tsp\Console\BranchBoundCommand;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

$application = new Application();
$application->add(new BranchBoundCommand());

try {
    $application->run();
} catch (Exception $e) {
    echo $e->getMessage();
}