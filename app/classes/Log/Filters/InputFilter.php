<?php

namespace Log\Filters;

use Obullo\Log\LogService,
    Obullo\Log\Filter\FilterInterface;

/**
 * Input Filter Class
 * 
 * @category  Log
 * @package   Filter
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/package/log
 */
Class InputFilter implements FilterInterface
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
        $this->params = $params;
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
        $notPermitted = array(  // Put your not permitted context data in here
            'session',
        );
        if (strpos($record['message'], '$_COOKIE:') === 0 
            OR strpos($record['message'], '$_POST:') === 0
            OR strpos($record['message'], '$_GET:') === 0
        ) {
            foreach ($notPermitted as $v) {
                if (isset($record['context'][$v])) {
                    unset($record['context'][$v]);
                }
            }
        }
        return $record;
    }

}

// END InputFilter class

/* End of file InputFilter.php */
/* Location: .Obullo/Log/Filter/InputFilter.php */