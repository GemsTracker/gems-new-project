<?php

declare(strict_types=1);

namespace App;

use App\Factory\ProjectOverloaderFactory;
use App\Handler\HomePageHandler;
use App\Handler\HomePageHandlerFactory;
use App\Handler\LegacyController;
use App\Handler\PingHandler;
use App\Legacy\LegacyControllerFactory;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Zalt\Loader\ProjectOverloader;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     * @return mixed[]
     */
    public function __invoke(): array
    {
        return [
            'db'           => $this->getDbSettings(),
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
            'routes'       => $this->getRoutes(),
        ];
    }

    /**
     * @return boolean[]|string[]
     */
    public function getDbSettings(): array
    {
        return [
            'driver'    => 'Mysqli',
            'host'      => getenv('DB_HOST'),
            'username'  => getenv('DB_USER'),
            'password'  => getenv('DB_PASS'),
            'database'  => getenv('DB_NAME'),
            'charset'   => 'utf8',
        ];
    }

    /**
     * Returns the container dependencies
     * @return mixed[]
     */
    public function getDependencies(): array
    {
        return [
            'factories'  => [
                ProjectOverloader::class => ProjectOverloaderFactory::class,
                HomePageHandler::class => HomePageHandlerFactory::class,
            ],
            'abstract_factories' => [
                ReflectionBasedAbstractFactory::class,
            ],
        ];
    }

    /**
     * Returns the route configuration
     *
     * @return mixed[]
     */
    public function getRoutes(): array
    {
        return [
            [
                'name' => 'home',
                'path' => '/',
                'middleware' => HomePageHandler::class,
                'allowed_methods' => ['GET'],
            ],
            [
                'name' => 'api.ping',
                'path' => '/api/ping',
                'middleware' => PingHandler::class,
                'allowed_methods' => ['GET'],
            ],
            [
                'name' => 'setup.reception.index',
                'path' => '/setup/reception/index',
                'middleware' => LegacyController::class,
                'allowed_methods' => ['GET'],
                'options' => [
                    'controller' => \Gems_Default_ReceptionAction::class,
                    'action' => 'index',
                ]
            ]
        ];
    }

    /**
     * Returns the templates configuration
     *
     * @return mixed[]
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'app'    => ['templates/app'],
                'error'  => ['templates/error'],
                'layout' => ['templates/layout'],
            ],
        ];
    }
}
