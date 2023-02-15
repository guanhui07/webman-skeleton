#!/usr/bin/env php
<?php
require_once __DIR__ . '/vendor/autoload.php';
use Hyperf\AopIntegration\ClassLoader;

define('PROJECT_ROOT',__DIR__);
ClassLoader::init();
support\App::run();
