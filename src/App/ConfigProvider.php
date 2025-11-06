<?php

declare(strict_types=1);

namespace App;

use App\Handler\HomeHandler;
use App\Handler\HomePageHandler;
use App\Handler\HomePageHandlerFactory;
use Gems\AuthNew\AuthenticationMiddleware;
use Gems\Helper\Env;
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
            'app'            => $this->getAppSettings(),
//            'auth'           => $this->getAuthSettings(),
            'cache'        => $this->getCacheSettings(),
//            'console'        => $this->getConsoleSettings(),
            'db'             => $this->getDbSettings(),
            'dependencies'   => $this->getDependencies(),
            'email'          => $this->getEmailSettings(),
            'interface'      => $this->getInterfaceSettings(),
//            'javascript'     => $this->getJavascript(),
            'locale'         => $this->getLocaleSettings(),
//            'model'          => $this->getModelSettings(),
//            'overLoaderPaths' => ['App'],
            'roles'           => $this->getRoles(),
            'routes'           => $this->getRoutes(),
            'security'         => $this->getSecuritySettings(),
            'templates'        => $this->getTemplates(),
//            'translations'     => $this->getTranslationSettings(),
            'twofactor'        => $this->getTwoFactor(),
        ];
    }

    protected function getAppSettings(): array
    {
        return [
            'name' => 'GemsTracker',
            'show_title' => true,
            'show_env' => 'short',
        ];
    }

    protected function getAuthSettings(): array
    {
        return [
            'allowLoginOnOtherOrganization' => true,
        ];
    }

    public function getCacheSettings(): array
    {
        return [
            'adapter' => 'file',
        ];
    }

    public function getConsoleSettings(): array
    {
        return [
            'resetPassword' => true,
        ];
    }

    /**
     * @return boolean[]|string[]
     */
    public function getDbSettings(): array
    {
        return [
            'driver'    => 'Mysqli',
            'host'      => Env::get('DB_HOST'),
            'username'  => Env::get('DB_USER'),
            'password'  => Env::get('DB_PASS'),
            'database'  => Env::get('DB_NAME'),
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

    public function getEmailSettings(): array
    {
        return  [
            'dsn' => 'smtp://localhost:25',
            'site' => 'dcsw@magnafacta.nl',
        ];
    }

    protected function getInterfaceSettings(): array
    {
        return [
            'autosearch' => true,
        ];
    }

    protected function getJavascript()
    {
        return [
            'translations' => [
                'Cp\Translate\JavascriptTranslations' => dirname(__DIR__) . '/cp-js/src/locales',
            ],
        ];
    }

    public function getLocaleSettings(): array
    {
        return [
            'availableLocales' => [
                'en',
                'nl',
//                'de',
//                'fr',
            ],
            'default' => 'en',
        ];
    }

    protected function getModelSettings()
    {
        return [
            'translateDatabaseFields' => true,
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
        ]);
    }

    protected function getSecuritySettings(): array
    {
        return [
            'headers' => // null,
                [
                    'default' => [
                        'Content-Security-Policy' => 'default-src \'none\'; script-src \'self\'; style-src \'self\' \'unsafe-inline\'; img-src \'self\' data:; font-src \'self\' data:; connect-src \'self\'; frame-src \'self\' cpregister.nl youtube.com; frame-ancestors \'self\'; form-action \'self\'; block-all-mixed-content; base-uri \'self\';',
                        'Permissions-Policy' => 'accelerometer=(), ambient-light-sensor=(), autoplay=(), battery=(), camera=(), cross-origin-isolated=(), display-capture=(), document-domain=(), encrypted-media=(), execution-while-not-rendered=(self), execution-while-out-of-viewport=(self), fullscreen=(self), geolocation=(), gyroscope=(), keyboard-map=(), magnetometer=(), microphone=(), midi=(), navigation-override=(self), payment=(), picture-in-picture=(), publickey-credentials-get=(self), screen-wake-lock=(), sync-xhr=(self), usb=(), web-share=(), xr-spatial-tracking=()',
                        'Referrer-Policy' => 'same-origin',
                        'Strict-Transport-Security' => 'max-age=31536000;includeSubDomains',
                        'X-Content-Type-Options' => 'nosniff',
                        'X-Frame-Options' => 'deny',
                    ],
                ],
        ];
    }

    protected function getSurveySettings(): array
    {
        return [
            'tracks' => ['afterChangeRoute' => 'respondent.show'],
        ];
    }

    /**
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

    protected function getTranslationSettings(): array
    {
        return [
            'paths' => [
                'cp' => [dirname(__DIR__) . '/languages'],
            ],
        ];
    }    /**
     * Returns the roles defined by this project
     *
     * @return mixed[]
     */
    public function getRoles(): array
    {
        return [
        ];
    }

    protected function getTwoFactor(): array
    {
        return [
            'methods' => [
                'SmsHotp' => [
                    'disabled' => true,
                ],
            ],
        ];
    }
}
