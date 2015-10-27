<?php

namespace Http\Middlewares;

use ReflectionClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\Container\ContainerInterface as Container;
use Obullo\Router\RouterInterface as Router;
use Obullo\View\TemplateInterface as Template;
use Obullo\Http\ControllerInterface as Controller;

class Annotation
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
    public function __construct(Container $c, Router $router)
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
    public function inject(Controller $controller)
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
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $reflector = new ReflectionClass($this->controller);
        $method = $this->router->getMethod();  // default index

        // if (! $reflector->hasMethod($method)) {  // Show404 if method doest not exist

        //     $body = $this->c['template']->get('404');

        //     return $response->withStatus(404)
        //         ->withHeader('Content-Type', 'text/html')
        //         ->withBody($body);
        // }
        $docs = new \Obullo\Annotations\Controller($this->c, $reflector);
        $docs->setMethod($method);
        $docs->parse();

        return $next($request, $response);
    }

}