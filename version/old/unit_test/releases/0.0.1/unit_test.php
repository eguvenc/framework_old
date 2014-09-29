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
    const PREFIX_KEY   = 'key';
    const PREFIX_VALUE = 'value';
    const PREFIX_DESC  = 'description';

    public $results         = array();

    public $set_test_name = '';
    public $set_test_desc = '';
    
    private $_total_passed  = 0;
    private $_total_failed  = 0;
    private $_mainTestName  = '';
    private $_template      = null;
    private $_template_rows = null;

    private static $_item_types   = array(
                        'line'      => 'Line',
                        'file'      => 'File',
                        'method'    => 'Method',
                        'result'    => 'Result',
                        'actual'    => 'Actual',
                        'expected'  => 'Expected',
                        'parameter' => 'Parameter',
                        'desc'      => 'Description',
                    );

    private static $_class_items = array(
                                    'failed' => 'warning_variable',
                                    'passed' => 'success_variable',
                    );

    /**
     * Constructor
     * 
     * @param string $testName main test name
     */
    public function __construct($testName = 'undefined')
    {
        global $logger;

        if ( ! isset(getInstance()->unit)) {
            getInstance()->unit = $this; // Make available it in the controller $this->form->method();
        }
        
        $this->_mainTestName = $testName;

        $logger->debug('Unit Testing Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Set Expected Item
     * 
     * @param string $key   expected key
     * @param string $value expected value
     * @param string $desc  expected desc
     * 
     * @return void
     */
    public function setExpected($key, $value, $desc)
    {
        // $this->results[self::$_item_types['expected']][$key] = array('key' => $key, 'expected_value' => $value, 'description' => $desc);
        $this->expected[$key] = array('key' => $key, 'expected_value' => $value, 'description' => $desc);
    }

    // --------------------------------------------------------------------

    /**
     * Set Actual Item
     * 
     * @param string $key   expected key
     * @param string $value expected value
     * @param string $desc  expected desc
     * 
     * @return void
     */
    public function setActual($key, $value, $desc)
    {
        $this->expected[$key]['actual_value'] = $value;
        // $this->results[self::$_item_types['actual']][$key] = array('key' => $key, 'actual_value' => $value, 'description' => $desc);
    }

    // --------------------------------------------------------------------

    /**
     * Set Test Name
     * 
     * @param string $name test name
     * 
     * @return void
     */
    public function setTestName($name = 'undefined')
    {
        $this->set_test_name = $name;
    }

    // --------------------------------------------------------------------

    /**
     * Set Test Type
     * 
     * @param string $type test type
     * 
     * @return void
     */
    public function setTestType($type = '')
    {
        $this->set_test_type = $description;
    }

    // --------------------------------------------------------------------

    /**
     * Set Test Descripton
     * 
     * @param string $description test description
     * 
     * @return void
     */
    public function setTestDesc($description = '')
    {
        $this->set_test_description = $description;
    }

    // --------------------------------------------------------------------

    /**
     * Set Return Value
     * 
     * @param string $return test result
     * 
     * @return void
     */
    public function setReturn($return = '')
    {
        $this->results[]['return'] = $return;
    }

    // --------------------------------------------------------------------

    /**
     * Run the tests
     * Runs the supplied tests
     * 
     * @param string $testName test name
     * 
     * @return void
     */
    public function run($testName = 'undefined')
    {
        $this->_actualResult();
        $this->report = $this->report();
        $this->_reset();
    }

    // --------------------------------------------------------------------

    /**
     * Reset
     * 
     * @return void
     */
    private function _reset()
    {
        $this->results        = array();
        $this->expected       = array();
        $this->set_test_name  = '';
        $this->set_test_desc  = '';
        $this->_total_passed  = 0;
        $this->_total_failed  = 0;
        $this->_mainTestName  = '';
        $this->_template      = null;
        $this->_template_rows = null;
    }

    /**
     * [_actualResult description]
     * 
     * @return [type] [description]
     */
    private function _actualResult()
    {
        foreach ($this->expected as $key => $val) {

            $this->results['expected_result'][$key] = array(self::PREFIX_KEY => $val['key'], self::PREFIX_VALUE => $val['expected_value'], self::PREFIX_DESC => $val['description']);

            if ($val['expected_value'] == $val['actual_value']) {
                $this->results['expected_result'][$key]['result'] = 'passed';
            } else {
                $this->results['expected_result'][$key]['result'] = 'failed';
            }

        }
    }

    // --------------------------------------------------------------------

    /**
     * Get Result
     * 
     * @return html
     */
    public function getResult()
    {
        /*$this->report[] = $this->report($this->lastResult($this->_backtrace()));

        if (is_array($this->report)) {
            $result = '';
            foreach ($this->report as $key => $value) {
                $result .= $value;
            }
            return $result;
        }*/
        return $this->report;
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
        $this->_parseTemplate();

        $r = '';
        foreach ($this->results as $result) {
            $table = '';
            foreach ($result as $key => $value) {

                $temp  = $this->_template_rows;

                if ($value['result'] == 'passed') {

                    $temp = str_replace('{class_item}', self::$_class_items['passed'], $temp);

                    $this->_total_passed = ($this->_total_passed + 1);
                    $val = '<span class="glyphicon glyphicon-ok"></span> ' . $value[self::PREFIX_VALUE];

                } elseif ($value['result'] == 'failed') {

                    $temp = str_replace('{class_item}', self::$_class_items['failed'], $temp);

                    $this->_total_failed = ($this->_total_failed + 1);
                    $val = '<span class="glyphicon glyphicon-remove"></span> ' . $value[self::PREFIX_VALUE];
                }

                $temp  = str_replace('{'.self::PREFIX_VALUE.'}', $val, $temp);
                $temp  = str_replace('{'.self::PREFIX_KEY.'}', $value[self::PREFIX_KEY], $temp);
                $temp  = str_replace('{'.self::PREFIX_DESC.'}', $value[self::PREFIX_DESC], $temp);
                $table.= $temp;
            }

            $r = $this->_template = str_replace('{template_rows}', $table, $this->_template);
        }
        return $r;
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
    /*public function result($results = array())
    {
        if (count($results) == 0) {
            $results = $this->results;
        }

        echo '<pre>';
        print_r($results);

        // die;
        $return = array();

        foreach ($results as $keys => $values) {

            if (is_array($values)) {

                foreach ($values as $key => $val) {

                    $return[$keys] = $val;

                    if (is_array($val)) {
                        foreach ($val as $k => $v) {
                            $return[$k] = $v;
                        }
                    }
                }
            }
        }
        // $return[] = $return;
        // 
        echo '<pre>';
        print_r($results);

        die;

        return $return;
    }*/

    // --------------------------------------------------------------------

    /**
     * Result Array
     * Returns the raw result data
     * 
     * @param array $backtrace line and file information
     *
     * @return array
     */
    public function lastResult($backtrace)
    {
        $return = array();
        
        $temp['main_test']    = $this->_mainTestName;
        $temp['total_failed'] = $this->_total_failed;
        $temp['total_passed'] = $this->_total_passed;
        $temp['line']         = $backtrace['line'];
        $temp['file']         = $backtrace['file'];
        $temp['result']       = ($this->_total_failed == 0) ? 'passed' : 'failed';

        $return[] = $temp;
        return $return;
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
     * @return array
     */
    private function _backtrace()
    {
        if (function_exists('debug_backtrace')) {
            $back = debug_backtrace();

            $file = ( ! isset($back['1']['file'])) ? '' : $back['1']['file'];
            $line = ( ! isset($back['1']['line'])) ? '' : $back['1']['line'];

            return array(self::$_item_types['file'] => $file, self::$_item_types['line'] => $line);
        }
        return array('file' => 'Unknown', 'line' => 'Unknown');
    }

    // --------------------------------------------------------------------

    /**
     * Get Default Template
     *
     * @return string
     */
    private function _defaultTemplate()
    {
        $this->_template       = '<pre><span class="center"><b>File Name </span> | '.__FILE__.'</b></pre>';
        $this->_template      .= '<pre><span class="center"><b>Type </span> | </span></b></pre>';
        $this->_template      .= '<pre><span class="center"><b>Last Run </span> | </span>24.05.2014</b></pre>';
        $this->_template      .= '<input type="hidden" name="selected_option" id="selected_option_run" value="{hidden_value}">';
        $this->_template      .= '<button id="step2" class="btn btn-success button-run" onclick="step2();" type="button">Run</button>';
        $this->_template      .= '<h3>Actual Results</h3>';
        $this->_template      .= '<table class="table global_table">';
        $this->_template      .= '<tr>';
        $this->_template      .= '<th>'.self::PREFIX_KEY.'</th>';
        $this->_template      .= '<th>'.self::PREFIX_VALUE.'</th>';
        $this->_template      .= '<th>'.self::PREFIX_DESC.'</th>';
        $this->_template      .= '{template_rows}';
        $this->_template      .= '</table>';
        $this->_template_rows  = '<tr class="{class_item}" data-toggle="tooltip" data-placement="right" title="">';
        $this->_template_rows .= '<td>{'.self::PREFIX_KEY.'}</td>';
        $this->_template_rows .= '<td>{'.self::PREFIX_VALUE.'}</td>';
        $this->_template_rows .= '<td>{'.self::PREFIX_DESC.'}</td>';
        $this->_template_rows .= '</tr>';
    }

    // --------------------------------------------------------------------

    /**
     * Parse Template
     *
     * Harvests the data within the template {pseudo-variables}
     *
     * @return void
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
    }

    // --------------------------------------------------------------------
    
    /**
     * Convert To String
     * 
     * @param unknown $test test
     * 
     * @return string serialize
     */
    public function convertToString($test)
    {
        return var_export($test, true);
    }

    // --------------------------------------------------------------------
    
    /**
     * Set Message
     * 
     * @param string $message set message
     * 
     * @return string serialize
     */
    public function setMessage($message)
    {
        $this->results[]['message'] = $message;
    }

    // --------------------------------------------------------------------
    

    /**
     * Is true to test boolean true/false
     *
     * @param boolean $test test value
     * @param string  $desc description
     * 
     * @return boolean true or false
     */
    public function isTrue($test, $desc = 'asds adas')
    {
        $back = $this->_backtrace();
        $isTrue = (is_bool($test) AND $test === true) ? true : false;
        $this->results[self::PREFIX_DESC][self::$_item_types['desc']]     = $desc;
        $this->results[self::PREFIX_KEY][self::$_item_types['line']]      = $back[self::$_item_types['line']];
        $this->results[self::PREFIX_KEY][self::$_item_types['line']]      = $back[self::$_item_types['line']];
        $this->results[self::PREFIX_KEY][self::$_item_types['file']]      = $back[self::$_item_types['file']];
        $this->results[self::PREFIX_KEY][self::$_item_types['parameter']] = $this->convertToString($test);
        $this->results[self::PREFIX_KEY][self::$_item_types['method']]    = ($isTrue === true) ? 'passed' : 'failed';
        return $isTrue;
    }

    // --------------------------------------------------------------------

    /**
     * Is false
     * 
     * @param boolean $test test
     * 
     * @return boolean true or false
     */
    public function isFalse($test)
    {
        $isFalse = (is_bool($test) AND $test === false) ? true : false;
        $this->results[]['line']            = $this->_backtrace();
        $this->results[]['parameter']       = $this->convertToString($test);
        $this->results['method']['isFalse'] = ($isFalse === true) ? 'passed' : 'failed';
        return $isFalse;
    }

    // --------------------------------------------------------------------
   
    /**
     * Is object
     * 
     * @param boolean $test test
     * 
     * @return boolean true or false
     */
    public function isObject($test)
    {
        $isObject = is_object($test);
        $this->results[]['line']             = $this->_backtrace();
        $this->results[]['parameter']        = $this->convertToString($test);
        $this->results['method']['isObject'] = ($isObject === true) ? 'passed' : 'failed';
        return $isObject;
    }

    // --------------------------------------------------------------------

    /**
     * Is bool
     * 
     * @param boolean $test test
     * 
     * @return boolean true or false
     */
    public function isBool($test)
    {
        $isBool = is_bool($test);
        $this->results[]['line']           = $this->_backtrace();
        $this->results[]['parameter']      = $this->convertToString($test);
        $this->results['method']['isBool'] = ($isBool === true) ? 'passed' : 'failed';
        return $isBool;
    }

    // --------------------------------------------------------------------

    /**
     * Is int
     * 
     * @param boolean $test test
     * 
     * @return boolean true or false
     */
    public function isInt($test)
    {
        $isInt = is_int($test);
        $this->results[]['line']          = $this->_backtrace();
        $this->results[]['parameter']     = $this->convertToString($test);
        $this->results['method']['isInt'] = ($isInt === true) ? 'passed' : 'failed';
        return $isInt;
    }

    // --------------------------------------------------------------------

    /**
     * Is numeric
     * 
     * @param boolean $test test
     * 
     * @return boolean true or false
     */
    public function isNumeric($test)
    {
        $isNumeric = is_numeric($test);
        $this->results[]['line']              = $this->_backtrace();
        $this->results[]['parameter']         = $this->convertToString($test);
        $this->results['method']['isNumeric'] = ($isNumeric === true) ? 'passed' : 'failed';
        return $isNumeric;
    }
    
    // --------------------------------------------------------------------

    /**
     * Is float
     * 
     * @param boolean $test test
     * 
     * @return boolean true or false
     */
    public function isFloat($test)
    {
        $isFloat = is_float($test);
        $this->results[]['line']            = $this->_backtrace();
        $this->results[]['parameter']       = $this->convertToString($test);
        $this->results['method']['isFloat'] = ($isFloat === true) ? 'passed' : 'failed';
        return $isFloat;
    }
    
    // --------------------------------------------------------------------

    /**
     * Is double
     * 
     * @param boolean $test test
     * 
     * @return boolean true or false
     */
    public function isDouble($test)
    {
        $isDouble = is_double($test);
        $this->results[]['line']             = $this->_backtrace();
        $this->results[]['parameter']        = $this->convertToString($test);
        $this->results['method']['isDouble'] = ($isDouble === true) ? 'passed' : 'failed';
        return $isDouble;
    }

    // --------------------------------------------------------------------

    /**
     * Is array
     * 
     * @param boolean $test test
     * 
     * @return boolean true or false
     */
    public function isArray($test)
    {
        $isArray = is_array($test);
        $this->results[]['line']            = $this->_backtrace();
        $this->results[]['parameter']       = $this->convertToString($test);
        $this->results['method']['isArray'] = ($isArray === true) ? 'passed' : 'failed';
        return $isArray;
    }
    
    // --------------------------------------------------------------------

    /**
     * Is null
     * 
     * @param boolean $test test
     * 
     * @return boolean true or false
     */
    public function isNull($test)
    {
        $isNull = is_null($test);
        $this->results[]['line']           = $this->_backtrace();
        $this->results[]['parameter']      = $this->convertToString($test);
        $this->results['method']['isNull'] = ($isNull === true) ? 'passed' : 'failed';
        return $isNull;
    }

    // --------------------------------------------------------------------

    /**
     * Is empty
     * 
     * @param boolean $test test
     * 
     * @return boolean true or false
     */
    public function isEmpty($test)
    {
        $isEmpty = empty($test);
        $this->results[]['line']            = $this->_backtrace();
        $this->results[]['parameter']       = $this->convertToString($test);
        $this->results['method']['isEmpty'] = ($isEmpty === true) ? 'passed' : 'failed';
        return $isEmpty;
    }

    // --------------------------------------------------------------------

    /**
     * Is empty
     * 
     * @param string $first  first
     * @param string $second second
     * 
     * @return boolean true or false
     */
    public function isEqual($first, $second)
    {
        $isEqual = false;
        if ($first == $second) {
            $isEqual = true;
        }
        $this->results[]['line']            = $this->_backtrace();
        $this->results[]['parameter']       = $this->convertToString($first);
        $this->results[]['parameter']       = $this->convertToString($second);
        $this->results['method']['isEqual'] = ($isEqual === true) ? 'passed' : 'failed';
        return $isEqual;
    }

    // --------------------------------------------------------------------

    /**
     * Is Regex Match
     * 
     * @param string $patern regex patern
     * @param string $match  data
     * 
     * @return boolean true or false
     */
    public function isRegexMatch($patern, $match)
    {
        $isRegexMatch = true;
        if ( ! preg_match($patern, $match)) {
            $isRegexMatch = false;
        }
        
        $this->results[]['line']                 = $this->_backtrace();
        $this->results[]['parameter']            = $this->convertToString($patern);
        $this->results[]['parameter']            = $this->convertToString($match);
        $this->results['method']['isRegexMatch'] = ($isRegexMatch === true) ? 'passed' : 'failed';
        return $isRegexMatch;
    }
}


// END Unit_Test Class

/* End of file unit_test.php */
/* Location: ./packages/unit_test/releases/0.0.1/unit_test.php */