<?php

namespace Log\Filters;

use Obullo\Log\Logger;
use Obullo\Container\Container;
use Obullo\Log\FilterInterface;
use Obullo\Log\Addons\LogPriorityFilterTrait;

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
     * Injected Parameters
     * 
     * @var array
     */
    public $params;

    /**
     * Constructor
     * 
     * @param object $c container
     */
    public function __construct(Container $c)
    {
        $this->c = $c;
    }
}

// END PriorityFilter class

/* End of file PriorityFilter.php */
/* Location: .app/Log/Filters/PriorityFilter.php */