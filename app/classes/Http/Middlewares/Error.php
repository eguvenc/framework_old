<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use LogicException;
use ErrorException;
use RuntimeException;
use Obullo\Container\ContainerAwareTrait;
use Obullo\Container\ContainerAwareInterface;
use Obullo\Http\Middleware\ErrorMiddlewareInterface;

/**
 * Catch middleware errors
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
        $container = $this->getContainer();

        if (is_string($error)) {  // Middleware errors
            echo $error;
        }
        if (is_object($error)) {
        
            if ($container->get('app')->getEnv() != 'production') {
                $exception = new \Obullo\Error\Exception;
                echo $exception->make($error);
            }
            /*
            | Exception Hierarchy
            |
            |   - Exception
            |       - ErrorException
            |       - LogicException
            |           - BadFunctionCallException
            |               - BadMethodCallException
            |           - DomainException
            |           - InvalidArgumentException
            |           - LengthException
            |           - OutOfRangeException
            |       - RuntimeException
            |           - PDOException
            |           - OutOfBoundsException
            |           - OverflowException
            |           - RangeException
            |           - UnderflowException
            |           - UnexpectedValueException
            */
            switch ($error) {
            case ($error instanceof ErrorException):
            case ($error instanceof RuntimeException):
            case ($error instanceof LogicException):
                $log = new \Obullo\Error\Log($container->get('logger'));
                $log->error($error);
                break;
            }
        }
        return $response;
    }
}