<?php

declare(strict_types=1);

namespace App\Middleware;

use Laminas\Permissions\Acl\Acl;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AclMiddleware implements MiddlewareInterface
{
    private Acl $acl;

    public function __construct(Acl $acl)
    {
        $this->acl = $acl;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var \Mezzio\Router\RouteResult $routeResult */
        $routeResult = $request->getAttribute('Mezzio\Router\RouteResult');
        $route = $routeResult->getMatchedRoute();
        $options = $route->getOptions();

        if (!empty($options['permission']) && !$this->acl->isAllowed('role-1', $options['permission'])) {
            throw new \Exception('Not allowed'); // TODO turn into a nice response
        }

        return $handler->handle($request);
    }
}
