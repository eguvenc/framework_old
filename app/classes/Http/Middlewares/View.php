<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class View
{
    /**
     * Inject controller object
     * 
     * @param \Obullo\Controller\Controller $controller object
     * 
     * @return void
     */
    public function inject($controller)
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
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        return $next($request, $response);
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