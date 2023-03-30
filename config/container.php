<?php

declare(strict_types=1);

use Laminas\ServiceManager\ServiceManager;
use Symfony\Component\Dotenv\Dotenv;
use Gems\Config\AutoConfigurator;

$dotenv = new Dotenv();
$dotenv->loadEnv(dirname(dirname(__FILE__)).'/.env');

// Load configuration
$config = require __DIR__ . '/config.php';

$autoConfigurator = new AutoConfigurator($config);
$config = $autoConfigurator->autoConfigure();

$dependencies                       = $config['dependencies'];
$dependencies['services']['config'] = $config;

// Build container
$container = new ServiceManager($dependencies);

// Set defaults
\Laminas\Validator\AbstractValidator::setDefaultTranslator(
    new \Gems\Translate\LaminasTranslator($container->get(\Symfony\Contracts\Translation\TranslatorInterface::class))
);

return $container;
