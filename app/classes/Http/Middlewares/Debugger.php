<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Container\ContainerAwareInterface;
use Obullo\Http\Middleware\TerminableInterface;
use Obullo\Http\Middleware\MiddlewareInterface;

use Obullo\Debugger\Websocket;
use Obullo\Log\LoggerInterface as Logger;
use Obullo\Config\ConfigInterface as Config;
use Obullo\Http\ControllerInterface as Controller;
use Obullo\Container\ContainerInterface as Container;

class Debugger implements MiddlewareInterface, ContainerAwareInterface, TerminableInterface
{
    protected $c;
    protected $websocket;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container object or null
     *
     * @return void
     */
    public function setContainer(Container $container = null)
    {
        $this->c = $container;
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
        $this->websocket = new Websocket(
            $this->c['app'],
            $this->c['config'],
            $this->c['logger.params']
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
        if ($this->c['app']->request->getUri()->segment(0) != 'debugger') {

            $body = $this->c['app']->response->getBody();
            
            $this->websocket->emit(
                (string)$body,
                $this->c['logger']->getPayload()
            );
        }
    }

}