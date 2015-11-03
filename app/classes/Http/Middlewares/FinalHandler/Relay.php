<?php

namespace Http\Middlewares\FinalHandler;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\Log\Benchmark;
use Obullo\Log\LoggerInterface as Logger;

/**
 * This middleware declared in index.php file
 */
class Relay
{
    /**
     * Container
     * 
     * @var object
     */
    protected $c;

    /**
     * Logger
     * 
     * @var object
     */
    protected $logger;

    /**
     * Options
     * 
     * @var array
     */
    protected $options;

    /**
     * Original response body size.
     *
     * @var int
     */
    protected $bodySize = 0;

    /**
     * Constructor
     * 
     * @param array                  $options  options
     * @param LoggerInterface        $logger   logger
     * @param null|ResponseInterface $response Original response, if any.
     */
    public function __construct(array $options, Logger $logger, Response $response = null)
    {
        $this->logger = $logger;
        $this->options = $options;
        $this->response = $response;

        if ($response) {
            $this->bodySize = $response->getBody()->getSize();
        }
    }

    /**
     * Invoke middleware
     * 
     * @param ServerRequestInterface $request  request
     * @param ResponseInterface      $response response
     * @param error                  $error    error
     * 
     * @return object ResponseInterface
     */
    public function __invoke(Request $request, Response $response, $error = null)
    {
        if ($error) {

            echo $error->getMessage();
        }

        $this->terminate($request);

        return $response;
    }

    /**
     * Finalize your application jobs
     * 
     * Close app benchmark & application logger
     * 
     * @param ServerRequestInterface $request request
     * 
     * @return void
     */
    protected function terminate($request)
    {
        Benchmark::end($request);

        $this->logger->shutdown();  // To catch all worker errors close logger by manually.
    }
}