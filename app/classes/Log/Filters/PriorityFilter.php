<?php

namespace Log\Filters;

use Obullo\Log\Logger;
use Obullo\Log\Addons\LogPriorityFilterTrait;
use Obullo\Log\Filter\FilterInterface;

/**
 * PriorityFilter Class
 * 
 * @category  Log
 * @package   ExampleFilter
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/package/log
 */
Class PriorityFilter implements FilterInterface
{
    use LogPriorityFilterTrait;

    /**
     * Container
     * 
     * @var object
     */
    public $c;

    /**
     * Filter Params
     * 
     * @var array
     */
    public $priorities;

    /**
     * Constructor
     * 
     * @param object $c      container
     * @param array  $params array
     */
    public function __construct($c, array $params = array())
    {
        $this->c = $c;
        $this->priorities = $params;
    }
}

// END PriorityFilter class

/* End of file PriorityFilter.php */
/* Location: .app/Log/Filters/PriorityFilter.php */