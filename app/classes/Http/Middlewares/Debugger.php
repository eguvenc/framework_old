<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use League\Container\ImmutableContainerAwareTrait;
use League\Container\ImmutableContainerAwareInterface;

use Obullo\Debugger\Websocket;
use Obullo\Http\Middleware\TerminableInterface;
use Obullo\Http\Middleware\MiddlewareInterface;

class Debugger implements MiddlewareInterface, ImmutableContainerAwareInterface, TerminableInterface
{
    use ImmutableContainerAwareTrait;
    
    /**
     * Websocket
     * 
     * @var object
     */
    protected $websocket;

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
        $container = $this->getContainer();
        $params    = $container->get('config')->load('debugger');

        $this->websocket = new Websocket(
            $container,
            $params,
            $container->get('logger.params')
        );
        $this->websocket->connect();

        $err = null;

        return $next($request, $response, $err);
    }

    /**
     * Close websocket & emit response body
     * 
     * @return void
     */
    public function terminate()
    {
        $container = $this->getContainer();

        if ($container->get('app')->request->getUri()->segment(0) != 'debugger') {

            $body = $container->get('app')->response->getBody();
            
            $this->websocket->emit(
                (string)$body,
                $container->get('logger')->getPayload()
            );
        }
    }

}