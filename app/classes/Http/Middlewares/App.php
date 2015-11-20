<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Container\ContainerAwareInterface;
use Obullo\Http\Middleware\MiddlewareInterface;
use Obullo\Http\Middleware\ControllerAwareInterface;
use Obullo\Container\ContainerInterface as Container;

/**
 * System adds this middleware end of the queue by default 
 * when the application is run.
 */
class App implements MiddlewareInterface, ContainerAwareInterface
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
        return $next($request, $this->run($request, $response));
    }

    /**
     * Run application
     *
     * @param ServerRequestInterface $request  request
     * @param ResponseInterface      $response response
     * 
     * @return mixed
     */
    protected function run(Request $request, Response $response)
    {
        $result = $this->c['app']->call($request, $response);

        if (! $result) {

            $body = $this->c['template']->make('404');
            
            return $response->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($body);
        }
        return $result;
    }

}