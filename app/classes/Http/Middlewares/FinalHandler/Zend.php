<?php

namespace Http\Middlewares\FinalHandler;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Exception;
use Obullo\Http\Zend\Stratigility\Utils;
use Obullo\Container\ContainerAwareTrait;
use Obullo\Container\ContainerAwareInterface;
use Obullo\Http\Middleware\TerminableInterface;

/**
 * This middleware called in index.php file 
 * using by middlewarePipe class.
 */
class Zend implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Request
     * 
     * @var object
     */
    protected $request;

    /**
     * Response
     * 
     * @var object
     */
    protected $response;

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
        $this->options  = $options;
        $this->response = $response;
        
        if ($response) {
            $this->bodySize = $response->getBody()->getSize();
        }
        register_shutdown_function(array($this, 'shutdown'));
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

        $response = $this->setCookies($response);
        
        if ($err) {
            return $this->handleError($err, $response);
        }
        return $response;
    }

    /**
     * Set cookie headers
     * 
     * @param Response $response http ressponse
     *
     * @return object response
     */
    protected function setCookies(Response $response)
    {
        if ($this->container->hasShared('cookie')) {

            $headers = $this->container->get('cookie')->getHeaders();

            if (! empty($headers)) {
                $response->setCookies($headers);
                return $response;
            }
        }
        return $response;
    }

    /**
     * Handle an error condition
     *
     * Use the $error to create details for the response.
     *
     * @param mixed             $error    error
     * @param ResponseInterface $response Response instance.
     * 
     * @return Http\Response
     */
    protected function handleError($error, Response $response)
    {
        $response = $response->withStatus(
            Utils::getStatusCode($error, $response)
        );
        $message = $response->getReasonPhrase() ?: 'Unknown Error';

        if ($this->container->get('env')->getValue() !== 'production') {

            $message = $this->createDevelopmentErrorMessage($error);

            if ($message instanceof Response) {
                return $message;
            }
        }
        $body = $this->container->get('view')->withStream($message)->get();

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

            $closure = new \Http\Middlewares\Error;
            $closure->setContainer($this->container);

            return $closure($error, $this->request, $this->response);

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
        foreach ($this->container->get('middleware')->getQueue() as $object) {  // Run terminable middlewares

            if ($object instanceof TerminableInterface) {
                $object->terminate();
            }
        }
    }

}