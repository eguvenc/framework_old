<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Middleware test
 */
class MethodNotAllowed
{
    /**
     * Method not allowed
     * 
     * @param object   $c    ContainerInterface
     * @param callable $next callable
     * 
     * @return object response
     */
    public function __invoke($c, callable $next)
    {
        $method = $c['request']->getMethod();

        echo 'Before MethodNotAllowed middleware !<br />';

        if (! in_array($method, ['GET', 'POST', 'PUT', 'DELETE'])) {  // Get injected parameters
            
            $c['response']->withStatus(405)->showError(
                sprintf(
                    "Http Error 405 %s method not allowed.", 
                    ucfirst($method)
                ),
                'Method Not Allowed'
            );
        }
        return $next($c);
    }
}