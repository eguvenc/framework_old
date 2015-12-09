<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Container\ContainerAwareInterface;
use Obullo\Http\Middleware\MiddlewareInterface;
use Obullo\Container\ContainerInterface as Container;

class Csrf implements MiddlewareInterface, ContainerAwareInterface
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
     * @param ServerRequestInterface $request  request
     * @param ResponseInterface      $response response
     * @param callable               $next     callable
     * 
     * @return object ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        if ($request->getMethod() == 'POST') {

            $csrf = $this->c['csrf'];
            $verify = $csrf->verify($request);

            if (false == $verify) {

                $this->c['logger']->channel('security');
                $this->c['logger']->debug('Csrf validation is failed.');

                if ($request->isAjax()) {

                    return $this->ajaxResponse($response);
                }

                return $this->htmlResponse($response);
            }
        }
        return $next($request, $response);
    }

    /**
     * Json response
     * 
     * @param Response $response response
     * 
     * @return object
     */
    protected function ajaxResponse(Response $response)
    {
        return $response->json(
            [
                'message' => 'The action you have requested is not allowed.'
            ]
        );
    }

    /**
     * Html response
     * 
     * @param Response $response response
     * 
     * @return object
     */
    protected function htmlResponse(Response $response)
    {
        $body = $this->c['template']->make(
            'error',
            [
                'header' => 'Security Error',
                'error' => 'The action you have requested is not allowed.'
            ]
        );
        return $response->withStatus(401)
            ->withHeader('Content-Type', 'text/html')
            ->withBody($body);
    }

}