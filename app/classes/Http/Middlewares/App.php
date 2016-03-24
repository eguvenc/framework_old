<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Exception;
use ErrorException;
use Obullo\Http\Middleware\MiddlewareInterface;
use Obullo\Container\ContainerAwareTrait;
use Obullo\Container\ContainerAwareInterface;

/**
 * System adds this middleware end of the queue by default 
 * when the application is run.
 */
class App implements MiddlewareInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Error
     * 
     * @var mixed
     */
    protected $err;

    /**
     * Constructor
     */
    public function __construct()
    {
        set_error_handler(array($this, 'handleError'));
        set_exception_handler(array($this, 'handleException'));
        register_shutdown_function(array($this, 'handleFatalError'));
    }

    /**
     * Error handler, convert all errors to exceptions
     * 
     * @param integer $level   name
     * @param string  $message error message
     * @param string  $file    file
     * @param integer $line    line
     * 
     * @return boolean whether to continue displaying php errors
     */
    public function handleError($level, $message, $file = '', $line = 0)
    {
        $this->err = new ErrorException($message, $level, 0, $file, $line);
    }

    /**
     * Exception error handler
     * 
     * @param Exception $e exception class
     * 
     * @return boolean
     */
    public function handleException(Exception $e)
    {
        $this->err = $e;
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
        $response = $this->run($request, $response);

        return $next($request, $response, $this->err);
    }

    /**
     * Run application
     *
     * @param ServerRequestInterface $request  request
     * @param ResponseInterface      $response response
     * 
     * @return mixed
     */
    protected function run(Request $request, Response $response)
    {
        $result = $this->getContainer()->get('app')->call($request, $response);

        if (! $result) {
            
            $body = $this->getContainer()
                ->get('view')
                ->withStream()
                ->get('templates::404');

            return $response->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($body);
        }
        return $result;
    }

    /**
     * Handle fatal errors
     * 
     * @return mixed
     */
    public function handleFatalError()
    {   
        if (null != $error = error_get_last()) {

            $app = $this->getContainer()->get('app');

            $e = new ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']);
            $exception = new \Obullo\Error\Exception;

            if ($app->getEnv() != 'production') {
                echo $exception->make($e);
            }
        }
    }

}