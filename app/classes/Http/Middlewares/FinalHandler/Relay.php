<?php

namespace Http\Middlewares\FinalHandler;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Exception;
use Obullo\Http\Zend\Stratigility\Utils;
use Obullo\Http\Middleware\TerminableInterface;
use Obullo\Container\ContainerInterface as Container;

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
     * Request
     * 
     * @var object
     */
    protected $request;

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
     * @param null|ResponseInterface $response Original response, if any.
     */
    public function __construct(array $options, Response $response = null)
    {
        $this->options = $options;

        if ($response) {
            $this->bodySize = $response->getBody()->getSize();
        }
        register_shutdown_function(array($this, 'shutdown'));
    }

    /**
     * Set container
     * 
     * @param Container|null $container container
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
     * @param error                  $err      error
     * 
     * @return object ResponseInterface
     */
    public function __invoke(Request $request, Response $response, $err = null)
    {   
        $this->request = $request;

        if ($err) {
            return $this->handleError($err, $request, $response);
        }
        return $this->setCookieHeaders($response);
    }
    
    /**
     * Handle an error condition
     *
     * Use the $error to create details for the response.
     *
     * @param mixed             $error    error
     * @param RequestInterface  $request  Request instance.
     * @param ResponseInterface $response Response instance.
     * 
     * @return Http\Response
     */
    protected function handleError($error, Request $request, Response $response)
    {
        $response = $response->withStatus(
            Utils::getStatusCode($error, $response)
        );
        $message = $response->getReasonPhrase() ?: 'Unknown Error';

        if ($this->c['app']->env() !== 'production') {

            $message = $this->createDevelopmentErrorMessage($error);
        
        } else {

            // Catch errors in production env
            
            // $this->app->logException($error);
            // echo ($error instanceof Exception) ? $error->getMessage() : $error;
        }

        $body = $this->c['template']->body($message);

        $response = $response->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->withBody($body);

        return $response;
    }

    /**
     * Create a complete error message for development purposes.
     *
     * Creates an error message with full error details:
     *
     * - If the error is an exception, creates a message that includes the full
     *   stack trace.
     * - If the error is an object that defines `__toString()`, creates a
     *   message by casting the error to a string.
     * - If the error is not an object, casts the error to a string.
     * - Otherwise, cerates a generic error message indicating the class type.
     *
     * In all cases, the error message is escaped for use in HTML.
     *
     * @param mixed $error error
     * 
     * @return string
     */
    protected function createDevelopmentErrorMessage($error)
    {
        if ($error instanceof Exception) {

            $exception = new \Obullo\Error\Exception;  // Create friendly errors
            $message = $exception->make($error);

            $this->c['app']->logException($error);

        } elseif (is_object($error) && ! method_exists($error, '__toString')) {
            $message = sprintf('Error of type "%s" occurred', get_class($error));
        } else {
            $message = (string) $error;
            $message = htmlspecialchars($message, ENT_QUOTES, 'utf-8');
        }
        return $message;
    }

    /**
     * Finalize your application jobs
     * 
     * Register fatal handler / close app benchmark / close logger 
     * 
     * @return void
     */
    public function shutdown()
    {
        $this->c['app']->registerFatalError();
        
        \Obullo\Log\Benchmark::end($this->request);

        $this->c['logger']->shutdown();
        
        foreach ($this->c['middleware']->getQueue() as $object) {  // Run terminable middlewares

            if ($object instanceof TerminableInterface) {
                $object->terminate();
            }
        }
    }
}