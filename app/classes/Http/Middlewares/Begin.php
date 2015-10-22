<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Obullo\Log\LoggerInterface;
use Obullo\Config\ConfigInterface;
use Obullo\Application\ApplicationInterface;
use Obullo\Application\Middlewares\BenchmarkTrait;

class Begin
{
    use BenchmarkTrait;

    /**
     * Application
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
     * @param ConfigInterface      $config config
     * @param LoggerInterface      $logger logger
     */
    public function __construct(ApplicationInterface $app, ConfigInterface $config, LoggerInterface $logger)
    {
        $this->app = $app;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * Inject controller object
     * 
     * @param \Obullo\Controller\Controller $controller object
     * 
     * @return void
     */
    public function inject($controller)
    {
        // ...   
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

        $response = $next($request, $this->app->call($response));

        // if ($response->getStatusCode() == 404) {
        //     // global $c;
        //     // $c['middleware']->add('NotFound');
        // }
        $request = $this->benchmarkEnd($request);

        $this->logger->shutdown(); // Manually shutdown logger to catch all worker errors.
        return $response;
    }
}