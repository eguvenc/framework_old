<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Container\ParamsAwareTrait;
use Obullo\Container\ParamsAwareInterface;
use League\Container\ImmutableContainerAwareTrait;
use League\Container\ImmutableContainerAwareInterface;

use Obullo\Http\Middleware\MiddlewareInterface;
use Obullo\Application\Middleware\MaintenanceTrait;

class Maintenance implements MiddlewareInterface, ImmutableContainerAwareInterface, ParamsAwareInterface
{
    use ImmutableContainerAwareTrait, ParamsAwareTrait;

    /**
     * Maintenance 
     * 
     * @var string
     */
    protected $maintenance;

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
        if ($this->check() == false) {
            
            $body = $this->c['template']->make('maintenance');

            return $response->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->withBody($body);
        }
        $err = null;

        return $next($request, $response, $err);
    }

    /**
     * Check applications
     *
     * @return void
     */
    public function check()
    {   
        $maintenance = $this->c['config']['maintenance'];  // Default loaded in config class.
        $maintenance['root']['regex'] = null;

        $domain = (isset($this->params['domain'])) ? $this->params['domain'] : null;
        
        foreach ($maintenance as $label) {
            if (! empty($label['regex']) && $label['regex'] == $domain) {  // If route domain equal to domain.php regex config
                $this->maintenance = $label['maintenance'];
            }
        }
        if ($this->checkRoot()) {
            return false;
        }
        if ($this->checkNodes()) {
            return false;
        }
        return true;
    }

    /**
     * Check root domain is down
     * 
     * @return boolean
     */
    public function checkRoot()
    {
        if ($this->c['config']['maintenance']['root']['maintenance'] == 'down') {  // First do filter for root domain
            return true;
        }
        return false;
    }

    /**
     * Check app nodes is down
     * 
     * @return boolean
     */
    public function checkNodes()
    {
        if (empty($this->maintenance)) {
            return false;
        }
        if ($this->maintenance == 'down') {
            return true;
        }
    }

}