<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Container\ContainerAwareInterface;
use Obullo\Http\Middleware\MiddlewareInterface;
use Obullo\Container\ContainerInterface as Container;

class Router implements MiddlewareInterface, ContainerAwareInterface
{
    protected $c;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container object or null
     *
     * @return void
     */
    public function setContainer(Container $container = null)
    {
        $this->c = $container;
    }

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
        if ($this->c['router']->getDefaultPage() == '') {

            $error = 'Unable to determine what should be displayed.';
            $error.= 'A default route has not been specified in the router middleware.';

            $body = $this->c['template']->make('error', ['error' => $error]);

            return $response->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($body);
        }
        
        $err = null;

        return $next($request, $response, $err);
    }
}