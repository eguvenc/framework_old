<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @copyright       Ersin Guvenc (c) 2009.
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Parser Class
 * 
 * @package       Obullo
 * @subpackage    Libraries
 * @category      Libraries
 * @author        Ersin Guvenc
 * @link          
 */
Class OB_Parser {

    public $l_delim = '{';
    public $r_delim = '}';
    public $object;
       
    // --------------------------------------------------------------------
        
    public function __construct() 
    {     
        log_me('debug', "Parser Class Initialized");
    }
        
    /**
    *  Parse a template
    *
    * Parses pseudo-variables contained in the specified template,
    * replacing them with the data in the second param
    *
    * @access   public
    * @param    string
    * @param    array
    * @param    bool
    * @return   string
    */
    public function parse($template, $data, $return = FALSE)
    {
        $template = view($template, $data);
        
        if ($template == '')
        {
            return FALSE;
        }
        
        foreach ($data as $key => $val)
        {
            if (is_array($val))
            {
                $template = $this->_parse_pair($key, $val, $template);        
            }
            else
            {
                $template = $this->_parse_single($key, (string)$val, $template);
            }
        }
        
        if ($return == FALSE)
        {
            this()->output->append_output($template);
        }
        
        return $template;
    }
    
    // --------------------------------------------------------------------
    
    /**
    *  Parse a String
    *
    * Parses pseudo-variables contained in the specified string,
    * replacing them with the data in the second param
    *
    * @access   public
    * @param    string
    * @param    array
    * @param    bool
    * @return   string
    */
    public function parse_string($template, $data, $return = FALSE)
    {
        return $this->_parse($template, $data, $return);
    }

    // --------------------------------------------------------------------
    
    /**
    *  Parse a template
    *
    * Parses pseudo-variables contained in the specified template,
    * replacing them with the data in the second param
    *
    * @access   public
    * @param    string
    * @param    array
    * @param    bool
    * @return   string
    */
    public function _parse($template, $data, $return = FALSE)
    {
        if ($template == '')
        {
            return FALSE;
        }

        foreach ($data as $key => $val)
        {
            if (is_array($val))
            {
                $template = $this->_parse_pair($key, $val, $template);        
            }
            else
            {
                $template = $this->_parse_single($key, (string)$val, $template);
            }
        }

        if ($return == FALSE)
        {
            this()->output->append_output($template);
        }

        return $template;
    }
    
    // --------------------------------------------------------------------
    
    /**
     *  Set the left/right variable delimiters
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   void
     */
    public function set_delimiters($l = '{', $r = '}')
    {
        $this->l_delim = $l;
        $this->r_delim = $r;
    }
    
    // --------------------------------------------------------------------
    
    /**
     *  Parse a single key/value
     *
     * @access   private
     * @param    string
     * @param    string
     * @param    string
     * @return   string
     */
    public function _parse_single($key, $val, $string)
    {
        return str_replace($this->l_delim.$key.$this->r_delim, $val, $string);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Parse a tag pair
     *
     * Parses tag pairs:  {some_tag} string... {/some_tag}
     *
     * @access   private
     * @param    string
     * @param    array
     * @param    string
     * @return   string
     */
    public function _parse_pair($variable, $data, $string)
    {    
        if (FALSE === ($match = $this->_match_pair($string, $variable)))
        {
            return $string;
        }

        $str = '';
        foreach ($data as $row)
        {
            $temp = $match['1'];
            foreach ($row as $key => $val)
            {
                if ( ! is_array($val))
                {
                    $temp = $this->_parse_single($key, $val, $temp);
                }
                else
                {
                    $temp = $this->_parse_pair($key, $val, $temp);
                }
            }
            
            $str .= $temp;
        }
        
        return str_replace($match['0'], $str, $string);
    }
    
    // --------------------------------------------------------------------
    
    /**
    *  Matches a variable pair
    *
    * @access   private
    * @param    string
    * @param    string
    * @return   mixed
    */
    public function _match_pair($string, $variable)
    {
        if ( ! preg_match("|" . preg_quote($this->l_delim) . $variable . preg_quote($this->r_delim) . "(.+?)". preg_quote($this->l_delim) . '/' . $variable . preg_quote($this->r_delim) . "|s", $string, $match))
        {
            return FALSE;
        }
        
        return $match;
    }

    
}
// END Parser Class

/* End of file Parser.php */
/* Location: ./obullo/libraries/Parser.php */