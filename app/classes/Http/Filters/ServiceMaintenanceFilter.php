<?php

namespace Http\Filters;

use LogicException,
    SimpleXmlElement;

/**
 * ServieMaintenance filter
 *
 * @category  Route
 * @package   Filters
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/router
 */
Class ServiceMaintenanceFilter
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
        $domain = $params['domain'];

        if ($domain != '*' AND ! $domain instanceof SimpleXmlElement) {
            throw new LogicException('Correct your routes.php domain option it must be like this $c[\'config\']->xml->service->$name.');
        }
        if ($domain == '*') {
            $name = 'all';
        } else {
            $name = $domain->getName();  // Get xml service name
        }
        if (isset($c['config']->xml->service->{$name}->domain->regex) 
            AND $c['config']->xml->service->{$name}->maintenance == 'down'
        ) {
            $c->load('response')->setHttpResponse(503);
            echo 'Service Unavailable !';
            die;
        }
    }
}

// END ServiceMaintenanceFilter class

/* End of file ServiceMaintenanceFilter.php */
/* Location: .Http/Filter/ServiceMaintenanceFilter.php */