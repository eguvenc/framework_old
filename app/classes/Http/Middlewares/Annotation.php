<?php

namespace Http\Middlewares;

use ReflectionClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\Http\Middleware\MiddlewareInterface;
use Obullo\Http\Middleware\ControllerAwareInterface;

use Obullo\Container\ContainerInterface as Container;
use Obullo\Router\RouterInterface as Route;
use Obullo\Http\ControllerInterface as Controller;

class Annotation implements MiddlewareInterface
{
    protected $c;
    protected $router;
    protected $controller;

    /**
     * Constructor
     * 
     * @param Container $c      container
     * @param Router    $router router
     */
    public function __construct(Container $c, Route $router)
    {
        $this->c = $c;
        $this->router = $router;
    }

    /**
     * Inject objects
     * 
     * @param object|null $controller controller instance
     * 
     * @return void
     */
    public function setController(Controller $controller)
    {        
        $this->controller = $controller;
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
        // return $next($request, $response);
        
        $reflector = new ReflectionClass($this->controller);
        $method = $this->router->getMethod();  // default index

        if (! $reflector->hasMethod($method)) {

            $body = $this->c['template']->make('404');

            return $response->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($body);
        }
        $docs = new \Obullo\Application\Annotations\Controller;
        $docs->setContainer($this->c);
        $docs->setReflectionClass($reflector);
        $docs->setMethod($method);
        $docs->parse();

        return $next($request, $response);
    }

}