<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use RuntimeException;
use Obullo\Url\UrlInterface as Url;
use Obullo\Router\RouterInterface as Router;
use Obullo\Http\Middleware\MiddlewareInterface;

class Https implements MiddlewareInterface
{
    protected $router;

    /**
     * Construct
     * 
     * @param Router $router router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
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
        $err = null;

        if ($request->isSecure() == false) {

            return $response->redirect('https://'.$this->router->getDomain()->getName() . $request->getUri()->getPath());
        }
        return $next($request, $response, $err);
    }

}