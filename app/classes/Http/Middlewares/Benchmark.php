<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\Log\LoggerInterface as Logger;
use Obullo\Config\ConfigInterface as Config;
use Obullo\Application\Middlewares\BenchmarkTrait;

class Benchmark
{
    use BenchmarkTrait;

    protected $logger;
    protected $config;

    /**
     * Constructor
     * 
     * @param Config $config config
     * @param Logger $logger logger
     */
    public function __construct(Config $config, Logger $logger)
    {
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
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $request = $this->benchmarkStart($request);

        // echo 'Before Benchmark <br>';

        $response = $next($request, $response);

        // echo 'After Benchmark <br>';
        
        $this->benchmarkEnd($request);

        $this->logger->shutdown();  // Manually shutdown logger to catch all worker errors.

        return $response;
    }
}