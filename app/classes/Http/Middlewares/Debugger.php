<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\Http\Middleware\MiddlewareInterface;
use Obullo\Http\ControllerInterface as Controller;
use Obullo\Application\ApplicationInterface as Application;
use Obullo\Log\LoggerInterface as Logger;
use Obullo\Config\ConfigInterface as Config;
use Obullo\Container\ContainerInterface as Container;
use Obullo\Debugger\Websocket;

class Debugger implements MiddlewareInterface
{
    protected $app;
    protected $config;
    protected $logger;
    protected $websocket;

    /**
     * Constructor
     * 
     * @param Container   $c      container
     * @param Application $app    application
     * @param Config      $config config
     * @param Logger      $logger logger
     */
    public function __construct(Container $c, Application $app, Config $config, Logger $logger)
    {
        $this->app = $app;
        $this->logger = $logger;
        $this->config = $config;
        $this->params = $c['logger.params'];

        register_shutdown_function(array($this, 'close'));
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
            $this->app,
            $this->config,
            $this->params
        );
        $this->websocket->connect();

        $response = $next($request, $response);
        return $response;
    }

    /**
     * Close websocket & emit response body
     * 
     * @return void
     */
    public function close()
    {
        if ($this->app->request->getUri()->segment(0) != 'debugger') {

            $body = $this->app->response->getBody();

            $this->websocket->emit(
                (string)$body,
                $this->logger->getPayload()
            );
        }
    }

}