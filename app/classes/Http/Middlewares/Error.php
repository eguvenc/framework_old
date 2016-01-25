<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\Http\Middleware\ErrorMiddlewareInterface;
use League\Container\ImmutableContainerAwareTrait;
use League\Container\ImmutableContainerAwareInterface;

/**
 * Catch middleware errors
 * 
 * Only available with Zend\Stratigility middleware.
 */
class Error implements ErrorMiddlewareInterface, ImmutableContainerAwareInterface
{
    use ImmutableContainerAwareTrait;

    /**
     * Invoke middleware
     * 
     * @param mixed         $error    string error or object of Exception
     * @param Request       $request  Psr\Http\Message\ServerRequestInterface
     * @param Response      $response Psr\Http\Message\ResponseInterface
     * @param callable|null $out      final handler
     * 
     * @return object response
     */
    public function __invoke($error, Request $request, Response $response, callable $out = null)
    {
        if (is_string($error)) {

            echo $error;
        }
        if (is_object($error)) {
            
            if ($this->getContainer()->get('app')->getEnv() == 'local') {

                $exception = new \Obullo\Error\Exception;
                echo $exception->make($error);

                $this->getContainer()->get('app')->exceptionError($error);  // Log exceptions using app/errors.php

            } else {
            
                echo $error->getMessage();

                $this->getContainer()->get('app')->exceptionError($error);  // Log exceptions using app/errors.php
            }
        }

        return $response;
    }
}