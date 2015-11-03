<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\Http\Middleware\MiddlewareInterface;
use Obullo\Http\Middleware\ControllerAwareInterface;
use Obullo\Container\ContainerInterface as Container;

/**
 * System adds this middleware end of the queue by default 
 * when the application is run.
 */
class App implements MiddlewareInterface
{
    protected $c;

    /**
     * Constructor
     * 
     * @param Container $c container
     */
    public function __construct(Container $c)
    {
        $this->c = $c;
    }

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
        return $next($request, $this->run($response));
    }

    /**
     * Run application
     * 
     * @param ResponseInterface $response response
     * 
     * @return mixed
     */
    protected function run(Response $response)
    {
        $result = $this->c['app']->call($response);

        if (! $result) {

            $body = $this->c['template']->make('404');
            
            return $response->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($body);
        }
        return $result;
    }

}