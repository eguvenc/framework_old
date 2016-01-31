<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Http\Middleware\MiddlewareInterface;
use League\Container\ImmutableContainerAwareTrait;
use League\Container\ImmutableContainerAwareInterface;

class Router implements MiddlewareInterface, ImmutableContainerAwareInterface
{
    use ImmutableContainerAwareTrait;

    /**
     * Invoke middleware
     * 
     * @param ServerRequestInterface $request  request
     * @param ResponseInterface      $response response
     * @param callable               $next     callable
     * 
     * @return object ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        if ($this->getContainer()->get('router')->getDefaultPage() == '') {

            $error = 'Unable to determine what should be displayed.';
            $error.= 'A default route has not been specified in the router middleware.';

            $body = $this->getContainer()->get('view')
                ->withStream()
                ->get(
                    'templates::error', 
                    [
                        'error' => $error
                    ]
                );

            return $response->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($body);
        }
        
        $err = null;

        return $next($request, $response, $err);
    }
}