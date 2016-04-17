<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Container\ContainerAwareTrait;
use Obullo\Container\ContainerAwareInterface;
use Obullo\Http\Middleware\MiddlewareInterface;

class Maintenance implements MiddlewareInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Maintenance 
     * 
     * @var string
     */
    protected $maintenance;

    /**
     * Parameters
     * 
     * @var array
     */
    protected $params = array();

    /**
     * Invoke middleware
     * 
     * @param ServerRequestInterface $request  request
     * @param ResponseInterface      $response respone
     * @param callable               $next     callable
     * @param array                  $params   params
     * 
     * @return object ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next = null, $params = array())
    {
        echo 'Maintenance';
        $this->params = $params;

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
        $maintenance = $this->getContainer()
            ->get('config')
            ->get('maintenance');
        
        $maintenance['root']['regex'] = null;
        $domain = (isset($this->params['domain'])) ? $this->params['domain'] : null;
        
        foreach ($maintenance as $label) {
            if (! empty($label['regex']) && $label['regex'] == $domain) {  // If route domain equal to 
                                                                           // maintenance.php regex value
                $this->maintenance = $label['maintenance'];
            }
        }
        if ($this->checkRoot($maintenance)) {
            return false;
        }
        if ($this->checkSubDomains()) {
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
     * Check app subdomain is down
     * 
     * @return boolean
     */
    public function checkSubDomains()
    {
        if (empty($this->maintenance)) {
            return false;
        }
        if ($this->maintenance == 'down') {
            return true;
        }
    }

}