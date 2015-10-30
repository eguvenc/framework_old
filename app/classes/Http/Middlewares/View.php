<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\Http\Middleware\MiddlewareInterface;
use Obullo\Http\ControllerInterface as Controller;
use Obullo\Http\Middleware\ControllerAwareInterface;

class View implements ControllerAwareInterface, MiddlewareInterface
{
    /**
     * Inject controller object
     * 
     * @param \Obullo\Controller\Controller $controller object
     * 
     * @return void
     */ 
    public function setController(Controller $controller)
    {
        $this->setupLayout($controller);
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
        $response = $next($request, $response);
        return $response;
    }

    /**
     * Assign layout variables
     * 
     * @param \Obullo\Http\Controller $controller object
     * 
     * @return void
     */
    protected function setupLayout(Controller $controller)
    {
        if (method_exists($controller, '__invoke')) {
            $controller();
        }
    }
}