<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Container\ContainerAwareTrait;
use Obullo\Container\ContainerAwareInterface;
use Obullo\Http\Middleware\MiddlewareInterface;
use Obullo\Authentication\Middleware\UniqueSessionTrait;

class Auth implements MiddlewareInterface, ContainerAwareInterface
{
    use ContainerAwareTrait, UniqueSessionTrait;

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
        echo 'Auth';

        if ($this->getContainer()->get('user')->identity->check()) {
    
            $this->killSessions();  // Terminate multiple logins
        }
        $err = null;

        return $next($request, $response, $err);
    }
}