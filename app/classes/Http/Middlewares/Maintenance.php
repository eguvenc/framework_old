<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Container\ParamsAwareTrait;
use Obullo\Container\ParamsAwareInterface;
use League\Container\ImmutableContainerAwareTrait;
use League\Container\ImmutableContainerAwareInterface;

use Obullo\Http\Middleware\MiddlewareInterface;

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
            
            $body = $this->getContainer()->get('view')
                ->withStream()
                ->get('templates::maintenance');

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
        $maintenance = $this->getContainer()->get('config')['maintenance'];  // Default loaded in config class.
        
        $maintenance['root']['regex'] = null;
        $params = $this->getParams();
        $domain = (isset($params['domain'])) ? $params['domain'] : null;
        
        foreach ($maintenance as $label) {
            if (! empty($label['regex']) && $label['regex'] == $domain) {  // If route domain equal to domain.php regex value
                $this->maintenance = $label['maintenance'];
            }
        }
        if ($this->checkRoot($maintenance)) {
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
     * @param array $maintenance config
     * 
     * @return boolean
     */
    public function checkRoot($maintenance)
    {
        if ($maintenance['root']['maintenance'] == 'down') {  // First do filter for root domain
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