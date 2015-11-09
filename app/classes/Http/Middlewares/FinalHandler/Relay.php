<?php

namespace Http\Middlewares\FinalHandler;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Exception;
use Obullo\Http\Zend\Stratigility\Utils;
use Obullo\Log\LoggerInterface as Logger;
use Obullo\Container\ContainerInterface as Container;
use Obullo\Application\ApplicationInterface as Application;

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
     * @param ApplicationInterface   $app      application
     * @param LoggerInterface        $logger   logger
     * @param null|ResponseInterface $response Original response, if any.
     */
    public function __construct(array $options, Application $app, Logger $logger, Response $response = null)
    {
        $this->app = $app;
        $this->logger = $logger;
        $this->options = $options;

        if ($response) {
            $this->bodySize = $response->getBody()->getSize();
        }
        register_shutdown_function(array($this, 'shutdown'));
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
        $this->request = $request;

        if ($err) {
            return $this->handleError($err, $request, $response);
        }

        return $response;
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

        if (! isset($this->options['env'])
            || $this->options['env'] !== 'production'
        ) {
            $message = $this->createDevelopmentErrorMessage($error);
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

            $message = $this->c['exception']->make($error);

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
     * Register fatal handler / close app benchmark / closelogger 
     * 
     * @return void
     */
    public function shutdown()
    {
        $this->app->registerFatalError();
        
        \Obullo\Log\Benchmark::end($this->request);

        $this->logger->shutdown();
    }

}