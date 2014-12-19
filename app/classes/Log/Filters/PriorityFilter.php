<?php

namespace Log\Filters;

use Obullo\Log\Logger,
    Obullo\Log\Filter\FilterInterface;

/**
 * PriorityFilter Class
 * 
 * @category  Log
 * @package   ExampleFilter
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT
 * @link      http://obullo.com/package/log
 */
Class PriorityFilter implements FilterInterface
{
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
    public $params;

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

    /**
     * Filter in array
     * 
     * @param array $record unformatted record data
     * 
     * @return array
     */
    public function filter(array $record)
    {
        $priority = Logger::$priorities[$record['level']];
        if (in_array($priority, $this->priorities)) {
            return $record;
        }
        return array();  // To remove the record we return to empty array.
    }

    /**
     * Filter "not" in array
     * 
     * @param array $record unformatted record data
     * 
     * @return array
     */
    public function notIn($record)
    {
        $priority = Logger::$priorities[$record['level']];
        if ( ! in_array($priority, $this->priorities)) {
            return $record;
        }
        return array();  // To remove the record we return to empty array.
    }

}

// END PriorityFilter class

/* End of file PriorityFilter.php */
/* Location: .app/Log/Filters/PriorityFilter.php */