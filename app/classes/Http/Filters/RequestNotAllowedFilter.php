<?php

namespace Http\Filters;

use Obullo\Container\Container,
    Obullo\Application\Addons\RequestMethodNotAllowedTrait;

/**
 * Check http request method
 *
 * @category  Request
 * @package   Filters
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/filters
 */
Class RequestNotAllowedFilter
{
    use RequestMethodNotAllowedTrait;  // You can add / remove addons.

    /**
     * Container
     * 
     * @var object
     */
    protected $c;

    /**
     * Router request allowed methods
     * 
     * @var array
     */
    protected $allowedMethods = array();

    /**
     * Constructor
     *
     * @param object $c      container
     * @param array  $params array router allowed methods
     */
    public function __construct(Container $c, $params = array())
    {
        $this->c = $c;
        $this->allowedMethods = $params['allowedMethods'];  // Get current route allowed methods from router
    }

    /**
     * Before the controller
     * 
     * @return void
     */
    public function before()
    {   
        $this->methodIsAllowed();
    }
    
}

// END RequestNotAllowedFilter class

/* End of file RequestNotAllowedFilter.php*/
/* Location: .Http/Filters/RequestNotAllowedFilter.php */