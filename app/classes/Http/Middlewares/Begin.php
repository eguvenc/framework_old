<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Obullo\Http\BenchmarkTrait;
use Obullo\Config\ConfigInterface;
use Obullo\Container\ContainerInterface;
use Obullo\Log\LoggerInterface;

class Begin
{
    use BenchmarkTrait;

    /**
     * Container
     * 
     * @var object
     */
    protected $c;

    /**
     * Config
     * 
     * @var object
     */
    protected $config;

    /**
     * Logger
     * 
     * @var object
     */
    protected $logger;

    /**
     * Constructor
     * 
     * @param ContainerInterface $c      config
     * @param ConfigInterface    $config config
     * @param LoggerInterface    $logger logger
     */
    public function __construct(ContainerInterface $c, ConfigInterface $config, LoggerInterface $logger)
    {
        $this->c = $c;
        $this->config = $config;
        $this->logger = $logger;
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
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $request = $this->benchmarkStart($request);

        return $next($request, $response);
    }
}