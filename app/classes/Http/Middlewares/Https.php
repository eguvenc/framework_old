<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Http\Middleware\MiddlewareInterface;

class Https implements MiddlewareInterface
{
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
        $err = null;

        if ($request->isSecure() == false) {

            $path = $request->getUri()->getPath();
            $host = $request->getUri()->getHost();

            return $response->redirect('https://'.$host.$path);
        }
        return $next($request, $response, $err);
    }

}