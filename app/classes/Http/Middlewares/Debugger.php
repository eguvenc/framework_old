<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use League\Container\ContainerAwareTrait;
use League\Container\ContainerAwareInterface;

use Obullo\Http\Middleware\TerminableInterface;
use Obullo\Http\Middleware\MiddlewareInterface;

use Obullo\Debugger\Websocket;
use Obullo\Log\LoggerInterface as Logger;
use Obullo\Config\ConfigInterface as Config;
use Obullo\Container\ContainerInterface as Container;

class Debugger implements MiddlewareInterface, ContainerAwareInterface, TerminableInterface
{
    use ContainerAwareTrait;

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
        $this->websocket = new Websocket(
            $this->container->get('app'),
            $this->container->get('config'),
            $this->container->get('logger.params')
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
        if ($this->container->get('app')->request->getUri()->segment(0) != 'debugger') {

            $body = $this->container->get('app')->response->getBody();
            
            $this->websocket->emit(
                (string)$body,
                $this->container->get('logger')->getPayload()
            );
        }
    }

}