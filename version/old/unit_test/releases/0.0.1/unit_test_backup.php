<?php

/**
 * Unit Test Class
 * 
 * @category  Core
 * @package   Obullo
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/obullo
 */
Class Unit_Test
{
    public $active                      = true;
    public $results                     = array();
    public $strict                      = false;
    
    private $_template                  = null;
    private $_template_rows             = null;
    private $_test_items_visible        = array();
    private static $_typeCastingMethods = array('isEmpty', 'isObject', 'isString',
                                                'isBool', 'isTrue', 'isFalse',
                                                'isInt', 'isNumeric', 'isFloat',
                                                'isDouble', 'isArray', 'isNull',
                                                'isRegex'
                                                );
    /**
     * Constructor
     */
    public function __construct()
    {
        global $logger;

        if ( ! isset(getInstance()->unit_test)) {
            getInstance()->unit_test = $this; // Make available it in the controller $this->form->method();
        }

        // These are the default items visible when a test is run.
        $this->_test_items_visible = array(
            'test_name',
            'test_datatype',
            'res_datatype',
            'result',
            'file',
            'line',
            'notes'
        );

        $logger->debug('Unit Testing Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Set Test Items
     * 
     * @param array $items items
     * 
     * @return void
     */
    public function setTestItems($items = array())
    {
        if ( ! empty($items) AND is_array($items)) {
            $this->_test_items_visible = $items;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Run the tests
     * Runs the supplied tests
     * 
     * @param all    $test     test data
     * @param string $method   method type
     * @param string $testName test name
     * @param string $notes    notes
     * 
     * @return html
     */
    public function run($test, $method = true, $testName = 'undefined', $notes = '')
    {
        if ($this->active == false) {
            return false;
        }

        if (in_array($method, self::$_typeCastingMethods, true)) {

            $extype = str_replace(array('true', 'false'), 'bool', $method);
            $method = $this->_underscore($method); // added underscore
            $result = ($this->$method($test));

        } else {

            if ($this->strict == true) {
                $result = ($test === $method) ? true : false;
            } else {
                $result = ($test == $method) ? true : false;
            }
            $extype = gettype($method);
        }

        $back = $this->_backtrace();

        $report[] = array(
            'test_name'     => $testName,
            'test_datatype' => gettype($test),
            'res_datatype'  => $extype,
            'result'        => ($result === true) ? 'passed' : 'failed',
            'file'          => $back['file'],
            'line'          => $back['line'],
            'notes'         => $notes
        );
        $this->results[] = $report;
        return($this->report($this->result($report)));
    }

    // --------------------------------------------------------------------
     
    /**
     * Underscore
     * 
     * @param string $method type casting method
     * 
     * @return string
     */
    private function _underscore($method)
    {
        return '_' . $method;
    }

    // --------------------------------------------------------------------

    /**
     * Generate a report
     * Displays a table with the test data
     *
     * @param array $result result
     * 
     * @return string
     */
    public function report($result = array())
    {
        if (count($result) == 0) {
            $result = $this->result();
        }

        $this->_parseTemplate();

        $r = '';
        foreach ($result as $res) {
            $table = '';
            foreach ($res as $key => $val) {
                if ($key == 'result') {
                    if ($val == 'passed') {
                        $val = '<span style="color: #0C0;">' . $val . '</span>';
                    } elseif ($val == 'failed') {
                        $val = '<span style="color: #C00;">' . $val . '</span>';
                    }
                }
                
                $temp  = $this->_template_rows;
                $temp  = str_replace('{item}', $key, $temp);
                $temp  = str_replace('{result}', $val, $temp);
                $table.= $temp;
            }

            $r .= str_replace('{rows}', $table, $this->_template);
        }
        return $r;
    }

    // --------------------------------------------------------------------

    /**
     * Use strict comparison
     * Causes the evaluation to use === rather than ==
     *
     * @param boolean $state state
     * 
     * @return  boolean
     */
    public function useStrict($state = true)
    {
        $this->strict = ($state == false) ? false : true;
    }

    // --------------------------------------------------------------------

    /**
     * Make Unit testing active
     * Enables/disables unit testing
     *
     * @param boolean $state state
     * 
     * @return null
     */
    public function active($state = true)
    {
        $this->active = ($state == false) ? false : true;
    }

    // --------------------------------------------------------------------

    /**
     * Result Array
     * Returns the raw result data
     *
     * @param array $results results
     * 
     * @return array
     */
    public function result($results = array())
    {
        if (count($results) == 0) {
            $results = $this->results;
        }

        $retval = array();
        foreach ($results as $result) {
            $temp = array();
            foreach ($result as $key => $val) {
                if ( ! in_array($key, $this->_test_items_visible)) {
                    continue;
                }

                if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        if (false !== ($line = strtolower($v))) {
                            $v = $line;
                        }
                        $temp[$k] = $v;
                    }
                } else {
                    if (false !== ($line = strtolower($val))) {
                        $val = $line;
                    }
                    $temp[$key] = $val;
                }
            }

            $retval[] = $temp;
        }

        return $retval;
    }

    // --------------------------------------------------------------------

    /**
     * Set the template
     * This lets us set the template to be used to display results
     * 
     * @param string $template template
     * 
     * @return void
     */
    public function setTemplate($template)
    {
        $this->_template = $template;
    }

    // --------------------------------------------------------------------

    /**
     * Generate a backtrace
     *
     * This lets us show file names and line numbers
     *
     * @return  array
     */
    private function _backtrace()
    {
        if (function_exists('debug_backtrace')) {
            $back = debug_backtrace();

            $file = (!isset($back['1']['file'])) ? '' : $back['1']['file'];
            $line = (!isset($back['1']['line'])) ? '' : $back['1']['line'];

            return array('file' => $file, 'line' => $line);
        }
        return array('file' => 'Unknown', 'line' => 'Unknown');
    }

    // --------------------------------------------------------------------

    /**
     * Get Default Template
     *
     * @return  string
     */
    private function _defaultTemplate()
    {
        $this->_template       = "\n" . '<table style="width:100%; font-size:small; margin:10px 0; border-collapse:collapse; border:1px solid #CCC;">';
        $this->_template      .= '{rows}';
        $this->_template      .= "\n" . '</table>';
        $this->_template_rows  = "\n\t" . '<tr>';
        $this->_template_rows .= "\n\t\t" . '<th style="text-align: left; border-bottom:1px solid #CCC;">{item}</th>';
        $this->_template_rows .= "\n\t\t" . '<td style="border-bottom:1px solid #CCC;">{result}</td>';
        $this->_template_rows .= "\n\t" . '</tr>';
    }

    // --------------------------------------------------------------------

    /**
     * Parse Template
     *
     * Harvests the data within the template {pseudo-variables}
     *
     * @return  void
     */
    private function _parseTemplate()
    {
        if ( ! is_null($this->_template_rows)) {
            return;
        }

        if (is_null($this->_template)) {
            $this->_defaultTemplate();
            return;
        }

        if ( ! preg_match("/\{rows\}(.*?)\{\/rows\}/si", $this->_template, $match)) {
            $this->_defaultTemplate();
            return;
        }

        $this->_template_rows = $match['1'];
        $this->_template = str_replace($match['0'], '{rows}', $this->_template);
    }

    // --------------------------------------------------------------------

    /**
     * Is true to test boolean true/false
     *
     * @param boolean $test test
     * 
     * @return boolean
     */
    private function _isTrue($test)
    {
        return (is_bool($test) AND $test === true) ? true : false;
    }

    // --------------------------------------------------------------------

    /**
     * Is false
     * 
     * @param boolean $test test
     * 
     * @return boolean
     */
    private function _isFalse($test)
    {
        return (is_bool($test) AND $test === false) ? true : false;
    }

    // --------------------------------------------------------------------

    /**
     * Is object
     * 
     * @param boolean $test test
     * 
     * @return boolean
     */
    private function _isObject($test)
    {
        return is_object($test);
    }

    // --------------------------------------------------------------------

    /**
     * Is bool
     * 
     * @param boolean $test test
     * 
     * @return boolean
     */
    private function _isBool($test)
    {
        return is_bool($test);
    }

    // --------------------------------------------------------------------

    /**
     * Is int
     * 
     * @param boolean $test test
     * 
     * @return boolean
     */
    private function _isInt($test)
    {
        return is_int($test);
    }

    // --------------------------------------------------------------------

    /**
     * Is numeric
     * 
     * @param boolean $test test
     * 
     * @return boolean
     */
    private function _isNumeric($test)
    {
        return is_numeric($test);
    }
    
    // --------------------------------------------------------------------

    /**
     * Is float
     * 
     * @param boolean $test test
     * 
     * @return boolean
     */
    private function _isFloat($test)
    {
        return is_float($test);
    }
    
    // --------------------------------------------------------------------

    /**
     * Is double
     * 
     * @param boolean $test test
     * 
     * @return boolean
     */
    private function _isDouble($test)
    {
        return is_double($test);
    }

    // --------------------------------------------------------------------

    /**
     * Is array
     * 
     * @param boolean $test test
     * 
     * @return boolean
     */
    private function _isArray($test)
    {
        return is_array($test);
    }
    
    // --------------------------------------------------------------------

    /**
     * Is null
     * 
     * @param boolean $test test
     * 
     * @return boolean
     */
    private function _isNull($test)
    {
        return is_null($test);
    }

    // --------------------------------------------------------------------

    /**
     * Is empty
     * 
     * @param boolean $test test
     * 
     * @return boolean
     */
    private function _isEmpty($test)
    {
        return empty($test);
    }

    // --------------------------------------------------------------------

    /**
     * Is Regex
     * 
     * @param array $data data
     * 
     * @return boolean true or false
     */
    private function _isRegex($data)
    {
        if ( ! preg_match($data['regex'], $data['match'])) {
            return false;
        }
        return true;
    }
}


// END Unit_Test Class

/* End of file unit_test.php */
/* Location: ./packages/unit_test/releases/0.0.1/unit_test.php */