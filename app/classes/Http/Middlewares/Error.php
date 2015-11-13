<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\Container\ContainerAwareInterface;
use Obullo\Container\ContainerInterface as Container;
use Obullo\Http\Middleware\ErrorMiddlewareInterface;

/**
 * Catch middleware errors
 * 
 * Only available with Zend\Stratigility middleware.
 */
class Error implements ErrorMiddlewareInterface, ContainerAwareInterface
{
    protected $c;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container object or null
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
            
            if ($this->c['app']->env() == 'local') {

                $exception = new \Obullo\Error\Exception;
                echo $exception->make($error);

                $this->c['app']->logException($error);  // Log exceptions using app/errors.php

            } else {
            
                echo $error->getMessage();

                $this->c['app']->logException($error);  // Log exceptions using app/errors.php
            }
        }

        return $response;
    }
}