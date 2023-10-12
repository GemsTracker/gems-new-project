<?php

declare(strict_types=1);

namespace App;

use App\Handler\HomeHandler;
use App\Handler\HomePageHandler;
use App\Handler\HomePageHandlerFactory;
use App\Handler\PingHandler;
use Gems\AuthNew\AuthenticationMiddleware;
use Gems\Middleware\LocaleMiddleware;
use Gems\Middleware\MenuMiddleware;
use Gems\Middleware\SecurityHeadersMiddleware;
use Gems\Util\RouteGroupTrait;
use Mezzio\Csrf\CsrfMiddleware;
use Mezzio\Flash\FlashMessageMiddleware;
use Mezzio\Session\SessionMiddleware;

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
            'locale'       => $this->getLocaleSettings(),
            'templates'    => $this->getTemplates(),
            'routes'       => $this->getRoutes(),
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
            'options'   => ['buffer_results' => true],
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
                HomePageHandler::class => HomePageHandlerFactory::class,
            ],
        ];
    }

    public function getLocaleSettings(): array
    {
        return [
            'availableLocales' => [
                'en',
                'nl',
                'de',
                'fr',
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
        return $this->routeGroup(['middleware' =>
            [
                SecurityHeadersMiddleware::class,
                SessionMiddleware::class,
                FlashMessageMiddleware::class,
                CsrfMiddleware::class,
                LocaleMiddleware::class,
                AuthenticationMiddleware::class,

                MenuMiddleware::class,
            ]
        ],
        [
            [
                'name' => 'home',
                'path' => '/',
                'middleware' => HomeHandler::class,
                'allowed_methods' => ['GET'],
            ],
            [
                'name' => 'api.ping',
                'path' => '/api/ping',
                'middleware' => PingHandler::class,
                'allowed_methods' => ['GET'],
            ],
        ]);
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
            ],
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
        ];
    }
}
