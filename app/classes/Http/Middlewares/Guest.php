<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Container\ContainerAwareTrait;
use Obullo\Container\ContainerAwareInterface;
use Obullo\Http\Middleware\MiddlewareInterface;

class Guest implements MiddlewareInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Redirect url
     */
    const REDIRECT_URI = '/examples/membership/login/index';

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
        echo 'Guest';
        return $next($request, $response, null);
        if ($this->getContainer()->get('user')->identity->guest()) {

            $this->getContainer()->get('flash')->info('Your session has been expired.');

            return $response->redirect(static::REDIRECT_URI);
        }
        $err = null;

        return $next($request, $response, $err);
    }
}