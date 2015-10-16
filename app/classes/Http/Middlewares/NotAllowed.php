<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class NotAllowed
{
    /**
     * Allowed http methods
     * 
     * @var array
     */
    protected $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE'];

    /**
     * Set allowed methods
     * 
     * @param array $methods allowed methods
     *
     * @return void
     */
    public function inject(array $methods)
    {
        $this->allowedMethods = $methods;
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
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $method = $request->getMethod();

        if (! in_array($method, $this->allowedMethods)) {
            
            $response->error('Http Error 405 method not allowed.', 405, 'Method Not Allowed');
            return $response;
        }
        return $next($request, $response);
    }
}