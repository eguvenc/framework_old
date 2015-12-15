<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use RuntimeException;
use Obullo\Http\Translation\TranslatorInterface as Translator;
use Obullo\Http\Middleware\MiddlewareInterface;

class RewriteLocale implements MiddlewareInterface
{
    protected $stop = false;
    protected $translator;
    protected $excludedMethods = array();

    /**
     * Construct
     * 
     * @param Translator $translator translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
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
        $err = null;
        $this->excludeMethods($request, ['post']);  // Ignore http post methods

        $locale = $request->getUri()->segment($this->config['uri']['segmentNumber']);  // Check the segment http://examples.com/en/welcome

        if ($this->stop) {
            return;
        }
        $languages  = $this->config['languages'];
        $middleware = $this->c['middleware'];

        if (! $middleware->has('Translation')) {
            throw new RuntimeException(
                'RewriteLocale middleware requires Translation middleware.'
            );
        }
        if (! isset($languages[$locale])) {

            return $response->redirect($this->translator->getLocale() . $request->getUri()->getPath());
        }

        return $next($request, $response, $err);
    }

    /**
     * On / off rewrite
     * 
     * @param boolean $stop on / off
     * 
     * @return void
     */
    public function stop($stop = true)
    {
        $this->stop = $stop;
    }

    /**
     * Ignore these methods
     * 
     * @param ServerRequestInterface $request request
     * @param array                  $methods get, post, put, delete
     * 
     * @return void
     */
    public function excludeMethods(Request $request, array $methods)
    {
        $this->excludedMethods = $methods;

        $method = strtolower($request->getMethod());
        if (in_array($method, $this->excludedMethods)) {  // Except methods
            $this->stop();
        }
    }

}