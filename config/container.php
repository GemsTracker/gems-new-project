<?php

declare(strict_types=1);

use Laminas\ServiceManager\ServiceManager;
use Symfony\Component\Dotenv\Dotenv;
use Gems\Config\AutoConfigurator;

$dotenv = new Dotenv();
$dotenv->loadEnv(dirname(dirname(__FILE__)).'/.env');

file_put_contents(__DIR__ . '/echo.txt', __CLASS__ . '->' . __FUNCTION__ . '(' . __LINE__ . '): ' .  PHP_OS . "\n", FILE_APPEND);

include_once __DIR__ . '/windows.config.php';

// Load configuration
$config = require __DIR__ . '/config.php';

$autoConfigurator = new AutoConfigurator($config);
$config = $autoConfigurator->autoConfigure();

$dependencies                       = $config['dependencies'];
$dependencies['services']['config'] = $config;

// Build container
return new ServiceManager($dependencies);
