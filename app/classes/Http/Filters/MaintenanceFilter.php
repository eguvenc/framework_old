<?php

namespace Http\Filters;

use LogicException;
use Obullo\Container\Container;
use Obullo\Application\Addons\UnderMaintenanceTrait;

class MaintenanceFilter
{
    use UnderMaintenanceTrait; // You can add / remove addons.

    /**
     * Injected parameters ( Route domain config.php )
     * 
     * @var object
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
        $this->rootDomainIsDown();
        $this->subDomainIsDown();
    }
}

// END MaintenanceFilter class

/* End of file MaintenanceFilter.php */
/* Location: .Http/Filters/MaintenanceFilter.php */