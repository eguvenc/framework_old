<?php

namespace Http\Filters;

/**
 * Https filter
 *
 * @category  Route
 * @package   Filters
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/router
 */
Class HttpsFilter
{
    /**
     * Container
     * 
     * @var object
     */
    protected $c;

    /**
     * Constructor
     *
     * @param object $c container
     */
    public function __construct($c)
    {
        $this->c = $c;
        $this->uri = $c['uri'];
        $this->url = $c->load('url');
        $this->router = $c['router'];
    }

    /**
     * Before the controller
     * 
     * @return void
     */
    public function before()
    {
        if ($this->c['request']->isSecure() == false) {
            $this->url->redirect('https://'.$this->router->getDomain() . $this->uri->getRequestUri());
        }
    }
}

// END HttpsFilter class

/* End of file HttpsFilter.php */
/* Location: .Http/Filters/HttpsFilter.php */