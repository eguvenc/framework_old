<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Container\ContainerAwareInterface;
use Obullo\Http\Middleware\ParamsAwareInterface;
use Obullo\Container\ContainerInterface as Container;

use Obullo\Http\Middleware\MiddlewareInterface;
use Obullo\Application\Middleware\MaintenanceTrait;

class Maintenance implements MiddlewareInterface, ParamsAwareInterface, ContainerAwareInterface
{
    use MaintenanceTrait;

    protected $c;
    protected $params;

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
     * Set allowed methods
     * 
     * @param array $params allowed methods
     *
     * @return void
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * Invoke middleware
     * 
     * @param ServerRequestInterface $request  request
     * @param ResponseInterface      $response respone
     * @param callable               $next     callable
     * 
     * @return object ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        if ($this->check() == false) {
            
            $body = $this->c['template']->make('maintenance');

            return $response->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($body);
        }
        $err = null;

        return $next($request, $response, $err);
    }
}