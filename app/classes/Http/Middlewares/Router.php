<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Container\ContainerAwareTrait;
use Obullo\Container\ContainerAwareInterface;
use Obullo\Http\Middleware\MiddlewareInterface;

class Router implements MiddlewareInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

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
        $container = $this->getContainer();

        $container->share('router', 'Obullo\Router\Router')
            ->withArgument($container)
            ->withArgument($container->get('request'))
            ->withArgument($container->get('logger'));

        $router = $container->get('router'); // Make global

        include APP .'routes.php';

        $container->get('router')->init();

        $this->middlewares($request, $router);

        $err = null;

        return $next($request, $response, $err);
    }

    protected function middlewares($request, $router)
    {
        global $app;

        $path = $request->getUri()->getPath();


        if ($attach = $router->getAttach()) {

            foreach ($attach->getArray() as $val) {

                $regex = str_replace('#', '\#', $val['attach']);  // Ignore delimiters
                $Class = '\Http\Middlewares\\'.$val['name'];

                if ($val['route'] == 'global' || $val['route'] == $path) {     // Literal match
                    
                    $app->add(new $Class($this->container), $val['params']);

                } elseif (ltrim($regex, '.') == '*' || preg_match('#'. $regex .'#', $path)
                ) {
                    
                    $app->add(new $Class($this->container), $val['params']);
                }
            }
        }
    }

}


