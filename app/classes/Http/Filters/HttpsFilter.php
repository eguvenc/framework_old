<?php

namespace Http\Filters;

use Obullo\Container\Container;

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
    public function __construct(Container $c)
    {
        $this->c = $c;
    }

    /**
     * Before the controller
     * 
     * @return void
     */
    public function before()
    {
        if ($this->c['request']->isSecure() == false) {
            $this->c['url']->redirect('https://'.$this->c['router']->getDomain() . $this->c['uri']->getRequestUri());
        }
    }
}

// END HttpsFilter class

/* End of file HttpsFilter.php */
/* Location: .Http/Filters/HttpsFilter.php */