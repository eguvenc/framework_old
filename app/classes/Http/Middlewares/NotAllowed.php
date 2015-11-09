<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\View\TemplateInterface as Template;
use Obullo\Http\Middleware\MiddlewareInterface;
use Obullo\Http\Middleware\ParamsAwareInterface;

class NotAllowed implements ParamsAwareInterface, MiddlewareInterface
{
    protected $template;
    protected $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE'];

    /**
     * Constructor
     * 
     * @param Template $template object
     */
    public function __construct(Template $template)
    {
        $this->template = $template;
    }

    /**
     * Set allowed methods
     * 
     * @param array $params allowed methods
     *
     * @return void
     */
    public function setParams(array $params)
    {
        $this->allowedMethods = $params;
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
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $method = $request->getMethod();

        if (! in_array($method, $this->allowedMethods)) {
            
            $body = $this->template->make(
                'error',
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
        return $next($request, $response);
    }
}