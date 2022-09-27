<?php

declare(strict_types=1);

use Laminas\ServiceManager\ServiceManager;

// Load configuration
$config = require __DIR__ . '/config.php';
$config['rootDir'] = dirname(__DIR__);
$config['publicDir'] = dirname(__DIR__) . '/public';

$dependencies                       = $config['dependencies'];
$dependencies['services']['config'] = $config;

// Build container
return new ServiceManager($dependencies);
