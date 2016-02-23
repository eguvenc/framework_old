<?php

namespace Log\Filters;

use Obullo\Log\AbstractFilter;

/**
 * Filter
 * 
 * @copyright 2009-2016 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
class Filter implements AbstractFilter
{
    /**
     * Handle record array
     * 
     * @param array $record log record
     * @param array $params possible parameters
     * 
     * @return array|null
     */
    public function method(array $record, $params = array())
    {
        /**
         * Filter operation.
         */
        
        return $record;
    }
}