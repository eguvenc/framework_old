<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\Http\Middleware\ErrorMiddlewareInterface;

/**
 * Catch middleware errors
 * 
 * Only availabe with Zend\Stratigility middleware.
 */
class Error implements ErrorMiddlewareInterface
{
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
        if (is_object($error)) {
            echo $error->getMessage();
        }
        if (is_string($error)) {
            echo $error;
        }
        return $response;
    }
}