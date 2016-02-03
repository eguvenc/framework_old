<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Http\Middleware\MiddlewareInterface;
use League\Container\ImmutableContainerAwareTrait;
use League\Container\ImmutableContainerAwareInterface;

/**
 * System adds this middleware end of the queue by default 
 * when the application is run.
 */
class App implements MiddlewareInterface, ImmutableContainerAwareInterface
{
    use ImmutableContainerAwareTrait;

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

        return $next($request, $response);
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

            $body = $this->getContainer()->get('view')->withStream()->get('templates::404');

            return $response->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($body);
        }
        return $result;
    }

}