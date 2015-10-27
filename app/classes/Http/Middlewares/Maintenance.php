<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\Container\ContainerInterface as Container;
use Obullo\Application\Middleware\MaintenanceTrait;
use Obullo\Http\Middleware\ParamsAwareInterface;

class Maintenance implements ParamsAwareInterface
{
    use MaintenanceTrait;

    protected $c;
    protected $options;

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
     * Set allowed methods
     * 
     * @param array $params allowed methods
     *
     * @return void
     */
    public function inject(array $params)
    {
        $this->options = $params;
    }

    /**
     * Invoke middleware
     * 
     * @param ServerRequestInterface $request  request
     * @param ResponseInterface      $response respone
     * @param callable               $next     callable
     * 
     * @return object ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        if ($this->check() == false) {
            
            $body = $this->c['template']->make('maintenance');

            return $response->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($body);
        }
        return $next($request, $response);
    }
}