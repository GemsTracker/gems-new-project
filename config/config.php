<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

// To enable or disable caching, set the `ConfigAggregator::ENABLE_CACHE` boolean in
// `config/autoload/local.php`.
$cacheConfig = [
    'config_cache_path' => 'data/cache/config-cache.php',
];

$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->loadEnv(dirname(dirname(__FILE__)).'/.env.example');

$aggregator = new ConfigAggregator([
    \Mezzio\Helper\ConfigProvider::class,
    // Include cache configuration
    new ArrayProvider($cacheConfig),
    \Mezzio\ConfigProvider::class,
    \Mezzio\Router\ConfigProvider::class,
    \Mezzio\Router\FastRouteRouter\ConfigProvider::class,
    \Mezzio\Twig\ConfigProvider::class,
    \Laminas\Diactoros\ConfigProvider::class,



    \Gems\ConfigProvider::class,
    \Gems\LegacyConfigProvider::class,
    \Gems\Dev\ConfigProvider::class,

    // Default App module config
    App\ConfigProvider::class,

    // Load application config in a pre-defined order in such a way that local settings
    // overwrite global settings. (Loaded as first to last):
    //   - `global.php`
    //   - `*.global.php`
    //   - `local.php`
    //   - `*.local.php`
    new PhpFileProvider(realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php'),

    // Load development config if it exists
    new PhpFileProvider(realpath(__DIR__) . '/development.config.php'),
], $cacheConfig['config_cache_path']);

return $aggregator->getMergedConfig();
