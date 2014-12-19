<?php

namespace Http\Filters;

/**
 * User auth authority filter
 *
 * @category  Route
 * @package   Filters
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/router
 */
Class AuthFilter
{
    /**
     * User service
     * 
     * @var object
     */
    protected $user;

    /**
     * Constructor
     *
     * @param object $c container
     * 
     * @return void
     */
    public function __construct($c)
    {
        $this->user = $c->load('return service/user');
        
        if ($this->user->identity->isAuthenticated()) {

        	// Do something
        }
    }
}

// END AuthFilter class

/* End of file AuthFilter.php */
/* Location: .Http/Filter/AuthFilter.php */