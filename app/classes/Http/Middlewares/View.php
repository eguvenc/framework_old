<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\Http\ControllerInterface as Controller;
use Obullo\Http\Middleware\ControllerAwareInterface;

class View implements ControllerAwareInterface
{
    /**
     * Inject controller object
     * 
     * @param \Obullo\Controller\Controller $controller object
     * 
     * @return void
     */ 
    public function inject(Controller $controller)
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
    public function __invoke(Request $request, Response $response, callable $next)
    {
        // echo 'Before View <br>';
        $response = $next($request, $response);
        // echo 'After View <br>';
        return $response;
    }

    /**
     * Assign layout variables
     * 
     * @param \Obullo\Controller\Controller $controller object
     * 
     * @return void
     */
    protected function setupLayout($controller)
    {
        if (method_exists($controller, '__invoke')) {
            $controller();
        }
    }
}