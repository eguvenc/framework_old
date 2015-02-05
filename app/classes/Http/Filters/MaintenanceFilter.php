<?php

namespace Http\Filters;

use LogicException,
    Obullo\Container\Container,
    Obullo\Application\Addons\UnderMaintenanceTrait;

/**
 * Maintenance filter
 *
 * @category  Route
 * @package   Filters
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/router
 */
Class MaintenanceFilter
{
    use UnderMaintenanceTrait;

    /**
     * Container
     * 
     * @var object
     */
    protected $c;

    /**
     * Route domain configuration
     * 
     * @var object
     */
    protected $params;

    /**
     * Constructor
     *
     * @param object $c      container
     * @param array  $params route config parameters
     */
    public function __construct(Container $c , $params = array())
    {
        $this->c = $c;
        $this->params = $params;
    }

    /**
     * Before the controller
     * 
     * @return void
     */
    public function before()
    {
        $this->rootDomainIsDown();
        $this->subDomainIsDown();
    }
}

// END MaintenanceFilter class

/* End of file MaintenanceFilter.php */
/* Location: .Http/Filters/MaintenanceFilter.php */