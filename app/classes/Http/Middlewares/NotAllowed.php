<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\View\Template\TemplateInterface as Template;

class NotAllowed implements ParamsAwareInterface
{
    protected $template;

    /**
     * Allowed http methods
     * 
     * @var array
     */
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
    public function inject(array $params)
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
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $method = $request->getMethod();

        if (! in_array($method, $this->allowedMethods)) {
            
            $body = $this->template->make('404');

            return $response->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($body);
        }
        return $next($request, $response);
    }
}