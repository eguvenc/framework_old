<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\Http\Middleware\MiddlewareInterface;
use Obullo\Router\RouterInterface as Route;
use Obullo\Config\ConfigInterface as Config;
use Obullo\Container\ContainerInterface as Container;

class Router implements MiddlewareInterface
{
    protected $c;
    protected $router;
    protected $config;

    /**
     * Constructor
     * 
     * @param Container $c      container
     * @param Route     $router router
     * @param Config    $config config
     */
    public function __construct(Container $c, Route $router, Config $config)
    {
        $this->c = $c;
        $this->router = $router;
        $this->config = $config;
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
        if ($this->router->getDefaultPage() == '') {

            $error = 'Unable to determine what should be displayed.';
            $error.= 'A default route has not been specified in the router middleware.';

            $body = $this->c['template']->make('error', ['error' => $error]);

            return $response->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($body);
        }

        return $next($request, $response);
    }
}