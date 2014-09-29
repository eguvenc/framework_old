<?php

namespace Http\Filters;

/**
 * Maintenance filter
 *
 * @category  Route
 * @package   Filters
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/router
 */
Class MaintenanceFilter
{
    /**
     * Constructor
     *
     * @param object $c      container
     * @param array  $params config parameters
     * 
     * @return void
     */
    public function __construct($c , $params = array())
    {
        $c->load('app')->down('app.down', $params['domain']);
    }
}

// END MaintenanceFilter class

/* End of file MaintenanceFilter.php */
/* Location: .Http/Filters/MaintenanceFilter.php */