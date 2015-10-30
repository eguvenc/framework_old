<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\Http\Middleware\MiddlewareInterface;
use Obullo\Http\Middleware\ControllerAwareInterface;
use Obullo\Container\ContainerInterface as Container;

class Call implements MiddlewareInterface
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
        $router = $this->c['router'];
        $middleware = $this->c['middleware'];

        include MODULES .$router->getModule('/').$router->getDirectory().'/'.$router->getClass().'.php';
        $className = '\\'.$router->getNamespace().'\\'.$router->getClass();
        
        // $check404Error = $this->dispatchController($className, $router);
        // if (! $check404Error) {
        //     return false;
        // }
        $controller = new $className;
        $reflector = new \ReflectionClass($controller);
        $method = $router->getMethod();  // default index

        if (! $reflector->hasMethod($method)) {
            $body = $this->c['template']->make('404');
            return $response->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($body);
        }
        $docs = new \Obullo\Application\Annotations\Controller;
        $docs->setContainer($this->c);
        $docs->setReflectionClass($reflector);
        $docs->setMethod($method);
        $docs->parse();

        $controller->__setContainer($this->c);
        $response = $next($request, $response);

        unset($this->c['response']);
        $this->c['response'] = function () use ($response) {
            return $response;
        };
        ECHO 'OUTPUT';
        $result = call_user_func_array(
            array(
                $controller,
                $method
            ),
            array_slice($controller->request->getUri()->getRoutedSegments(), 3)
        );
        if ($result instanceof Response) {
            $response = $result;
        }
        return $response;
    }

}