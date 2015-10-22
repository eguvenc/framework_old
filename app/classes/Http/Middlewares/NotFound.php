<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\Http\Stream;
use Obullo\View\TemplateInterface as Template;

class NotFound
{
    protected $template;

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
        $body = $this->template->make('404');

        return $response->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->withBody($body);
    }
}