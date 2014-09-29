<?php

namespace Http\Filters;

/**
 * Route authority filter
 *
 * @category  Route
 * @package   Filters
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/router
 */
Class AuthFilter
{
    /**
     * Constructor
     *
     * @param object $c container
     * 
     * @return void
     */
    public function __construct($c)
    {
        $user = $c->load('return service/auth/user');
        
        if ($user->identity->isAuthenticated()) {

        	// Do something
        }
    }
}

// END AuthFilter class

/* End of file AuthFilter.php */
/* Location: .Http/Filter/AuthFilter.php */