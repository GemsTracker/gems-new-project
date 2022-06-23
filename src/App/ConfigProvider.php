<?php

declare(strict_types=1);

namespace App;

use App\Factory\ProjectOverloaderFactory;
use App\Handler\HomePageHandler;
use App\Handler\HomePageHandlerFactory;
use App\Handler\LegacyController;
use App\Handler\PingHandler;
use App\Legacy\LegacyControllerFactory;
use Gems\Middleware\AclMiddleware;
use App\Middleware\SecurityHeadersMiddleware;
use Gems\Util\RouteGroupTrait;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Mezzio\Helper\ContentLengthMiddleware;
use Zalt\Loader\ProjectOverloader;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    use RouteGroupTrait;

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
            'permissions'  => $this->getPermissions(),
            'roles'        => $this->getRoles(),
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
                'options' => [
                    'permission' => 'p-permission-2',
                ]
            ],
            ...$this->routeGroup([
                'path' => '/api',
                'middleware' => [AclMiddleware::class],
                'options' => [
                    'permission' => 'p-permission-2',
                ]
            ], [
                [
                    'name' => 'api.ping2',
                    'path' => '/ping2',
                    'middleware' => PingHandler::class,
                    'allowed_methods' => ['GET'],
                ],
            ])
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

    /**
     * Returns the permissions defined by this module
     *
     * @return mixed[]
     */
    public function getPermissions(): array
    {
        return [
            'p-permission-1',
            'p-permission-2',
            'p-permission-3',
        ];
    }

    /**
     * Returns the roles defined by this project
     *
     * @return mixed[]
     */
    public function getRoles(): array
    {
        return [
            'role-1' => ['p-permission-1', 'p-permission-2', 'gt.setup'],
            'role-2' => ['p-permission-2', 'p-permission-3'],
            'role-3' => ['gt.setup'],
        ];
    }
}
