<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Container\ParamsAwareTrait;
use Obullo\Container\ParamsAwareInterface;
use Obullo\Http\Middleware\MiddlewareInterface;
use League\Container\ImmutableContainerAwareTrait;
use League\Container\ImmutableContainerAwareInterface;

class NotAllowed implements MiddlewareInterface, ImmutableContainerAwareInterface, ParamsAwareInterface
{
    use ImmutableContainerAwareTrait, ParamsAwareTrait;

    /**
     * Allowed http methods
     * 
     * @var array
     */
    protected $allowedMethods = array(
        'GET',
        'POST',
        'PUT',
        'DELETE'
    );

    /**
     * Invoke middleware
     * 
     * @param ServerRequestInterface $request  request
     * @param ResponseInterface      $response respone
     * @param callable               $next     callable
     * 
     * @return object ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $method = $request->getMethod();

        if (! in_array($method, $this->getAllowedMethods())) {
            
            $body = $this->getContainer()->get('view')
                ->withStream()
                ->get(
                    'templates::error',
                    [
                        'error' => sprintf(
                            '%s Method Not Allowed',
                            $method
                        )
                    ]
                );
            return $response->withStatus(405)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($body);
        }
        $err = null;

        return $next($request, $response, $err);
    }

    /**
     * Get allowed allowed methods
     * 
     * @return void
     */
    protected function getAllowedMethods()
    {
        return array_map(
            function ($value) { 
                return strtoupper($value);
            },
            $this->getParams()
        );
    }

}