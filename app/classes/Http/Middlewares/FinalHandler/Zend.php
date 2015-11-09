<?php

namespace Http\Middlewares\FinalHandler;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\Http\Zend\Stratigility\Http\Request as DecoratedRequest;
use Obullo\Http\Zend\Stratigility\Http\Response as DecoratedResponse;

use Exception;
use Obullo\Log\Benchmark;
use Obullo\Http\Zend\Stratigility\Utils;
use Obullo\Log\LoggerInterface as Logger;
use Obullo\Http\Zend\Stratigility\FinalHandlerTrait;

/**
 * This middleware called in index.php file 
 * using by middlewarePipe class.
 */
class Zend
{
    use FinalHandlerTrait;

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
     * Set container
     * 
     * @param Container|null $c container
     *
     * @return void
     */
    public function setContainer(Container $c = null)
    {
        $this->c = $c;
    }

    /**
     * Invoke middleware
     * 
     * @param ServerRequestInterface $request  request
     * @param ResponseInterface      $response response
     * @param error                  $err      error
     * 
     * @return object ResponseInterface
     */
    public function __invoke(Request $request, Response $response, $err = null)
    {   
        if ($err) {
            return $this->handleError($err, $request, $response);
        }

        // Return provided response if it does not match the one provided at
        // instantiation; this is an indication of calling `$next` in the final
        // registered middleware and providing a new response instance.
        
        if ($this->response && $this->response !== $response) {
            $this->terminate($request);
            return $response;
        }
        
        // If the response passed is the same as the one at instantiation,
        // check to see if the body size has changed; if it has, return
        // the response, as the message body has been written to.
    
        if ($this->response
            && $this->response === $response
            && $this->bodySize !== $response->getBody()->getSize()
        ) {
            $this->terminate($request);
            return $response;
        }

        $response = $this->create404($request, $response);

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