<?php

namespace Http\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Obullo\Router\RouterInterface as Route;
use Obullo\Config\ConfigInterface as Config;
use Obullo\View\TemplateInterface as Template;
use Obullo\Application\ApplicationInterface as Application;

class Router
{
    protected $app;
    protected $router;
    protected $config;
    protected $template;

    /**
     * Constructor
     * 
     * @param Application $app      app
     * @param Route       $router   router
     * @param Config      $config   config
     * @param Template    $template template
     */
    public function __construct(Application $app, Route $router, Config $config, Template $template)
    {
        $this->app = $app;
        $this->router = $router;
        $this->config = $config;
        $this->template = $template;
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
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $this->router->configuration(
            [
                'domain' => 'framework',
                'defaultPage' => 'welcome',
            ]
        );
        if (strpos($request->getUri()->getHost(), $this->config['url']['webhost']) === false) {

            $error = 'Your webhost configuration is not correct in the main config file.';
            $body = $this->template->make('error', ['error' => $error]);

            return $response->withStatus(500)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($body);
        }

        if ($this->router->getDefaultPage() == '') {

            $error = 'Unable to determine what should be displayed.';
            $error.= 'A default route has not been specified in the router middleware.';

            $body = $this->template->make('error', ['error' => $error]);

            return $response->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($body);
        }
        $this->router->init();

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
        $result = $this->app->call($response);

        if (! $result) {
            $body = $this->template->make('404');
            return $response->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($body);
        }
        return $result;
    }

}