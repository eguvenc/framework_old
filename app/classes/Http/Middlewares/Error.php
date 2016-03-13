<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\Container\ContainerAwareTrait;
use Obullo\Container\ContainerAwareInterface;
use Obullo\Http\Middleware\ErrorMiddlewareInterface;

/**
 * Catch middleware errors
 * 
 * Only available with Zend\Stratigility middleware.
 */
class Error implements ErrorMiddlewareInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

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
        if (is_string($error)) {  // middleware errors
            echo $error;
        }
        if (is_object($error)) {
        
            $exception = new \Obullo\Error\Exception;
            echo $exception->make($error);  // display exceptions

            $this->getContainer()->get('app')->exceptionError($error);  // log exceptions to app/errors.php
        }
        return $response;
    }
}