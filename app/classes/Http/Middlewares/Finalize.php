<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Obullo\Http\BenchmarkTrait;
use Obullo\Config\ConfigInterface;
use Obullo\Log\LoggerInterface;
use Obullo\Application\ApplicationInterface;

class Finalize
{
    use BenchmarkTrait;

    /**
     * Config
     * 
     * @var object
     */
    protected $app;

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
     * @param ApplicationInterface $app    application
     * @param ContainerInterface   $config config
     * @param LoggerInterface      $logger logger
     */
    public function __construct(ApplicationInterface $app, ConfigInterface $config, LoggerInterface $logger)
    {
        $this->app = $app;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * Invoke middleware
     * 
     * @param ServerRequestInterface $request  request
     * @param ResponseInterface      $response response
     * 
     * @return object ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {   
        $this->app->call();
        
        $request = $this->benchmarkEnd($request);

        $this->logger->shutdown(); // Manually shutdown logger to catch worker errors.
        return $response;
    }
}