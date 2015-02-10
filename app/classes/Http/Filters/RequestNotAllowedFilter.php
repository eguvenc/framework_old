<?php

namespace Http\Filters;

use Obullo\Container\Container;
use Obullo\Application\Addons\RequestMethodNotAllowedTrait;

class RequestNotAllowedFilter
{
    use RequestMethodNotAllowedTrait;  // You can add / remove addons.

    /**
     * Injected parameters ( router inject filter allowed methods parameters in here )
     * 
     * @var array
     */
    public $params = array();

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
        $this->methodIsAllowed();
    }
    
}

// END RequestNotAllowedFilter class

/* End of file RequestNotAllowedFilter.php*/
/* Location: .Http/Filters/RequestNotAllowedFilter.php */