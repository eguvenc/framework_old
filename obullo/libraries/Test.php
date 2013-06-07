<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @copyright       Obullo Team
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Unit Testing Class
 *
 * Simple Unit Test
 *
 * @package       Obullo
 * @subpackage    Libraries
 * @category      UnitTesting
 * @author        Ersin Guvenc
 * @link          
 */
Class OB_Test {

    public $active            = TRUE;
    public $results           = array();
    public $strict            = FALSE;
    public $_template         = NULL;
    public $_template_rows    = NULL;
    
    public function __construct()
    {
        log_me('debug', "Unit Testing Class Initialized");
    }    

    // --------------------------------------------------------------------
    
    /**
     * Run the tests
     *
     * Runs the supplied tests
     *
     * @access   public
     * @param    mixed
     * @param    mixed
     * @param    string
     * @return   string
     */    
    public function run($test, $expected = TRUE, $test_name = 'undefined')
    {
        if ($this->active == FALSE)
        {
            return FALSE;
        }
    
        if (in_array($expected, array('is_object', 'is_string', 'is_bool', 'is_true', 'is_false', 'is_int', 'is_numeric', 'is_float', 'is_double', 'is_array', 'is_null'), TRUE))
        {
            $expected = str_replace('is_float', 'is_double', $expected);
            $result   = ($expected($test)) ? TRUE : FALSE;    
            $extype   = str_replace(array('true', 'false'), 'bool', str_replace('is_', '', $expected));
        }
        else
        {
            if ($this->strict == TRUE)
                $result = ($test === $expected) ? TRUE : FALSE;    
            else
                $result = ($test == $expected) ? TRUE : FALSE;    
            
            $extype = gettype($expected);
        }
                
        $back = $this->_backtrace();
    
        $report[] = array (
                            'test_name'         => $test_name,
                            'test_datatype'     => gettype($test),
                            'res_datatype'      => $extype,
                            'result'            => ($result === TRUE) ? 'passed' : 'failed',
                            'file'              => $back['file'],
                            'line'              => $back['line']
                        );

        $this->results[] = $report;        
                
        return($this->report($this->result($report)));
    }

    // --------------------------------------------------------------------
    
    /**
     * Generate a report
     *
     * Displays a table with the test data
     *
     * @access    public
     * @return    string
     */
    public function report($result = array())
    {
        if (count($result) == 0)
        {
            $result = $this->result();
        }
        
        loader::lang('ob/test');

        $this->_parse_template();

        $r = '';
        foreach ($result as $res)
        {
            $table = '';

            foreach ($res as $key => $val)
            {

                if ($key == lang('ut_result'))
                {
                    if ($val == lang('ut_passed'))
                    {
                        $val = '<span style="color: #0C0;">'.$val.'</span>';
                    }
                    elseif ($val == lang('ut_failed'))
                    {
                        $val = '<span style="color: #C00;">'.$val.'</span>';
                    }
                }

                $temp = $this->_template_rows;
                $temp = str_replace('{item}', $key, $temp);
                $temp = str_replace('{result}', $val, $temp);
                $table .= $temp;
            }

            $r .= str_replace('{rows}', $table, $this->_template);
        }

        return $r;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Use strict comparison
     *
     * Causes the evaluation to use === rather than ==
     *
     * @access   public
     * @param    bool
     * @return   null
     */
    public function use_strict($state = TRUE)
    {
        $this->strict = ($state == FALSE) ? FALSE : TRUE;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Make Unit testing active
     *
     * Enables/disables unit testing
     *
     * @access   public
     * @param    bool
     * @return   null
     */
    public function active($state = TRUE)
    {
        $this->active = ($state == FALSE) ? FALSE : TRUE;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Result Array
     *
     * Returns the raw result data
     *
     * @access    public
     * @return    array
     */
    public function result($results = array())
    {    
        loader::lang('ob/test');
        
        if (count($results) == 0)
        {
            $results = $this->results;
        }
        
        $retval = array();
        foreach ($results as $result)
        {
            $temp = array();
            foreach ($result as $key => $val)
            {
                if (is_array($val))
                {
                    foreach ($val as $k => $v)
                    {
                        if (FALSE !== ($line = lang(strtolower('ut_'.$v))))
                        {
                            $v = $line;
                        }                
                        $temp[lang('ut_'.$k)] = $v;                    
                    }
                }
                else
                {
                    if (FALSE !== ($line = lang(strtolower('ut_'.$val))))
                    {
                        $val = $line;
                    }                
                    $temp[lang('ut_'.$key)] = $val;
                }
            }
            
            $retval[] = $temp;
        }
    
        return $retval;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Set the template
     *
     * This lets us set the template to be used to display results
     *
     * @access   public
     * @param    string
     * @return   void
     */    
    public function set_template($template)
    {
        $this->_template = $template;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Generate a backtrace
     *
     * This lets us show file names and line numbers
     *
     * @access    private
     * @return    array
     */
    private function _backtrace()
    {
        if (function_exists('debug_backtrace'))
        {
            $back = debug_backtrace();
            
            $file = ( ! isset($back['1']['file'])) ? '' : $back['1']['file'];
            $line = ( ! isset($back['1']['line'])) ? '' : $back['1']['line'];
                        
            return array('file' => $file, 'line' => $line);
        }
        return array('file' => 'Unknown', 'line' => 'Unknown');
    }

    // --------------------------------------------------------------------
    
    /**
     * Get Default Template
     *
     * @access    private
     * @return    string
     */
    private function _default_template()
    {    
        $this->_template = "\n".'<table style="width:100%; font-size:small; margin:10px 0; border-collapse:collapse; border:1px solid #CCC;">';
        $this->_template .= '{rows}';
        $this->_template .= "\n".'</table>';
        
        $this->_template_rows = "\n\t".'<tr>';
        $this->_template_rows .= "\n\t\t".'<th style="text-align: left; border-bottom:1px solid #CCC;">{item}</th>';
        $this->_template_rows .= "\n\t\t".'<td style="border-bottom:1px solid #CCC;">{result}</td>';
        $this->_template_rows .= "\n\t".'</tr>';    
    }
    
    // --------------------------------------------------------------------

    /**
     * Parse Template
     *
     * Harvests the data within the template {pseudo-variables}
     *
     * @access    private
     * @return    void
     */
     private function _parse_template()
     {
         if ( ! is_null($this->_template_rows))
         {
             return;
         }
         
         if (is_null($this->_template))
         {
             $this->_default_template();
             return;
         }
         
        if ( ! preg_match("/\{rows\}(.*?)\{\/rows\}/si", $this->_template, $match))
        {
             $this->_default_template();
             return;
        }

        $this->_template_rows = $match['1'];
        $this->_template = str_replace($match['0'], '{rows}', $this->_template);     
     }
     
}
// END Unit_test Class

/**
* Helper functions to test boolean true/false
*
*
* @access    private
* @return    bool
*/
function is_true($test)
{
    return (is_bool($test) AND $test === TRUE) ? TRUE : FALSE;
}

function is_false($test)
{
    return (is_bool($test) AND $test === FALSE) ? TRUE : FALSE;
}


/* End of file Test.php */
/* Location: ./obullo/libraries/Test.php */