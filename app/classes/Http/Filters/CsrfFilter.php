<?php

namespace Http\Filters;

/**
 * Csrf security filter
 *
 * @category  Route
 * @package   Filters
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT
 * @link      http://obullo.com/docs/router
 */
Class CsrfFilter
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
        $c->load('security/csrf')->init();  
        $c->load('security/csrf')->verify(); // Csrf protection check
    }
}

// END CsrfFilter class

/* End of file CsrfFilter.php */
/* Location: .Http/Filter/CsrfFilter.php */