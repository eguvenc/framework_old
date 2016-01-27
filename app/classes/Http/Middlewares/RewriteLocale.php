<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use RuntimeException;
use Obullo\Http\Middleware\MiddlewareInterface;
use League\Container\ImmutableContainerAwareTrait;
use League\Container\ImmutableContainerAwareInterface;
use Obullo\Http\Translation\TranslatorInterface as Translator;

class RewriteLocale implements MiddlewareInterface, ImmutableContainerAwareInterface
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
        $err = null;

        if (! $this->getContainer()->get('middleware')->isAdded('Translation')) {

            throw new RuntimeException(
                'RewriteLocale middleware requires Translation middleware.'
            );
        }
        if ($request->getMethod() == 'GET') {

            $params  = $this->getContainer()->get('translator.params');
            $segment = $request->getUri()->segment($params['uri']['segment']);  // Get segment http://examples.com/en/welcome  (en)

            if (! in_array($segment, $params['default']['languages'])) {

                $translator = $this->getContainer()->get('translator');
                $path = $translator->getLocale().$request->getUri()->getPath();

                return $response->redirect($request->getUri()->withPath($path));
            }
        }
        return $next($request, $response, $err);
    }
}