<?php

namespace Http\Filters;

use Obullo\Container\Container;
use Obullo\Application\Addons\RequestMethodNotAllowedTrait;

class RequestNotAllowedFilter
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