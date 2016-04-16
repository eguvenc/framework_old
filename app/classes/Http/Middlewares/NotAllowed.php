<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Container\ContainerAwareTrait;
use Obullo\Container\ContainerAwareInterface;
use Obullo\Http\Middleware\MiddlewareInterface;

class NotAllowed implements MiddlewareInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Invoke middleware
     * 
     * @param ServerRequestInterface $request  request
     * @param ResponseInterface      $response respone
     * @param callable               $next     callable
     * @param array                  $methods  allowed methods
     * 
     * @return object ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next = null, $methods = array())
    {
        $body = $this->getContainer()
            ->get('view')
            ->withStream()
            ->get(
                'templates::error',
                [
                    'error' => sprintf(
                        'Method must be one of : %s',
                        implode(', ', $methods)
                    )
                ]
            );
            
        return $response
            ->withStatus(405)
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader('Content-Type', 'text/html')
            ->withBody($body);
    }

}