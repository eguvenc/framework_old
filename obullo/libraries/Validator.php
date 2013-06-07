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
 * Validator Class
 *
 * @package       Obullo
 * @subpackage    Base
 * @category      Validation
 * @author        Ersin Guvenc
 * @link        
 */
Class OB_Validator {
    
    public $_field_data         = array();    
    public $_config_rules       = array();
    public $_error_array        = array();
    public $_error_messages     = array();    
    public $_error_prefix       = '<p>';
    public $_error_suffix       = '</p>';
    public $error_string        = '';
    public $_safe_form_data     = FALSE;
    public $_globals            = array();  // $_POST, $_GET ,$_FILES
    public $_callback_object    = NULL;
    
    public function __construct($rules = array())
    {    
        // Validation rules can be stored in a config file.
        $this->_config_rules = $rules;
        
        // Automatically load the form helper
        loader::helper('ob/form');
            
        // Set the character encoding in MB.
        if (function_exists('mb_internal_encoding'))
        {
            mb_internal_encoding(lib('ob/Config')->item('charset'));
        }
    
        log_me('debug', "Validator Class Initialized");
    }

    // --------------------------------------------------------------------

    public function clear()
    {
        $this->_field_data         = array();
        $this->_config_rules       = array();
        $this->_error_array        = array();
        $this->_error_messages     = array();
        $this->_error_prefix       = '<p>';
        $this->_error_suffix       = '</p>';
        $this->_error_string        = '';
        $this->_safe_form_data     = FALSE;
        $this->_globals            = array();  // $_POST, $_GET ,$_FILES
        $this->_callback_object    = NULL;
    }

    // --------------------------------------------------------------------
    
    /**
    * Set validator class properties.
    * 
    * @param mixed $key
    * @param mixed $val
    */
    public function set($key, $val)
    {
        $this->$key = $val;
    }
    
    /**
     * Set Rules
     *
     * This function takes an array of field names and validation
     * rules as input, validates the info, and stores it
     *
     * @access   public
     * @param    mixed
     * @param    string
     * @return   void
     */
    public function set_rules($field, $label = '', $rules = '')
    {
        // Obullo Changes .. 
        $this->_globals = (count($this->_globals) == 0) ? $_POST : $this->_globals;
        
        // No reason to set rules if we have no POST or GET data
        if (count($this->_globals) == 0)
        {
            return;
        }
    
        // If an array was passed via the first parameter instead of indidual string
        // values we cycle through it and recursively call this function.
        if (is_array($field))
        {
            foreach ($field as $row)
            {
                // Houston, we have a problem...
                if ( ! isset($row['field']) OR ! isset($row['rules']))
                {
                    continue;
                }

                // If the field label wasn't passed we use the field name
                $label = ( ! isset($row['label'])) ? $row['field'] : $row['label'];

                // Here we go!
                $this->set_rules($row['field'], $label, $row['rules']);
            }
            return;
        }
        
        // No fields? Nothing to do...
        if ( ! is_string($field) OR  ! is_string($rules) OR $field == '')
        {
            return;
        }

        // If the field label wasn't passed we use the field name
        $label = ($label == '') ? $field : $label;

        // Is the field name an array?  We test for the existence of a bracket "[" in
        // the field name to determine this.  If it is an array, we break it apart
        // into its components so that we can fetch the corresponding POST data later        
        if (strpos($field, '[') !== FALSE AND preg_match_all('/\[(.*?)\]/', $field, $matches))
        {    
            // Note: Due to a bug in current() that affects some versions
            // of PHP we can not pass function call directly into it
            $x = explode('[', $field);
            $indexes[] = current($x);

            for ($i = 0; $i < count($matches['0']); $i++)
            {
                if ($matches['1'][$i] != '')
                {
                    $indexes[] = $matches['1'][$i];
                }
            }
            
            $is_array = TRUE;
        }
        else
        {
            $indexes     = array();
            $is_array    = FALSE;        
        }
        
        // Build our master array        
        $this->_field_data[$field] = array(
                                            'field'                => $field, 
                                            'label'                => $label, 
                                            'rules'                => $rules,
                                            'is_array'            => $is_array,
                                            'keys'                => $indexes,
                                            'postdata'            => NULL,
                                            'error'                => ''
                                            );
    }

    // --------------------------------------------------------------------
    
    /**
     * Set Error Message
     *
     * Lets users set their own error messages on the fly.  Note:  The key
     * name has to match the  function name that it corresponds to.
     *
     * @access    public
     * @param    string
     * @param    string
     * @return    string
     */
    public function set_message($lang, $val = '')
    {
        if ( ! is_array($lang))
        {
            $lang = array($lang => $val);
        }
    
        $this->_error_messages = array_merge($this->_error_messages, $lang);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Set The Error Delimiter
     *
     * Permits a prefix/suffix to be added to each error message
     *
     * @access    public
     * @param    string
     * @param    string
     * @return    void
     */    
    public function set_error_delimiters($prefix = '<p>', $suffix = '</p>')
    {
        $this->_error_prefix = $prefix;
        $this->_error_suffix = $suffix;
    }

    // --------------------------------------------------------------------
    
    /**
     * Get Error Message
     *
     * Gets the error message associated with a particular field
     *
     * @access    public
     * @param    string    the field name
     * @return    void
     */    
    public function error($field = '', $prefix = '', $suffix = '')
    {    
        if ( ! isset($this->_field_data[$field]['error']) OR $this->_field_data[$field]['error'] == '')
        {
            return '';
        }
        
        if($prefix == NULL AND $suffix == NULL)
        {
            return $this->_field_data[$field]['error'];
        }
        
        if ($prefix == '')
        {
            $prefix = $this->_error_prefix;
        }

        if ($suffix == '')
        {
            $suffix = $this->_error_suffix;
        }

        return $prefix.$this->_field_data[$field]['error'].$suffix;
    }

    // --------------------------------------------------------------------
    
    /**
     * Error String
     *
     * Returns the error messages as a string, wrapped in the error delimiters
     *
     * @access    public
     * @param    string
     * @param    string
     * @return    str
     */    
    public function error_string($prefix = '', $suffix = '')
    {
        // No errrors, validation passes!
        if (count($this->_error_array) === 0)
        {
            return '';
        }
        
        if ($prefix == '')
        {
            $prefix = $this->_error_prefix;
        }

        if ($suffix == '')
        {
            $suffix = $this->_error_suffix;
        }
        
        // Generate the error string
        $str = '';
        foreach ($this->_error_array as $val)
        {
            if ($val != '')
            {
                $str .= $prefix.$val.$suffix."\n";
            }
        }
        
        return $str;
    }

    // --------------------------------------------------------------------
    
    /**
     * Run the Validator
     *
     * This function does all the work.
     *
     * @access    public
     * @return    bool
     */        
    public function run($group = '')
    {
        // Do we even have any data to process?  Mm?
        if (count($this->_globals) == 0)
        {
            return FALSE;
        }
        
        // Does the _field_data array containing the validation rules exist?
        // If not, we look to see if they were assigned via a config file
        if (count($this->_field_data) == 0)
        {
            // No validation rules?  We're done...
            if (count($this->_config_rules) == 0)
            {
                return FALSE;
            }
            
            // Is there a validation rule for the particular URI being accessed?
            $uri = ($group == '') ? trim(lib('ob/Uri')->ruri_string(), '/') : $group;
            
            if ($uri != '' AND isset($this->_config_rules[$uri]))
            {
                $this->set_rules($this->_config_rules[$uri]);
            }
            else
            {
                $this->set_rules($this->_config_rules);
            }
    
            // We're we able to set the rules correctly?
            if (count($this->_field_data) == 0)
            {
                log_me('debug', "Unable to find validation rules");
                return FALSE;
            }
        }
    
        // Load the language file containing error messages
        loader::lang('ob/Validator');

        // Cycle through the rules for each field, match the 
        // corresponding $this->_globals item and test for errors

       
        
        foreach ($this->_field_data as $field => $row)
        {        
            // Fetch the data from the corresponding $this->_globals array and cache it in the _field_data array.
            // Depending on whether the field name is an array or a string will determine where we get it from.
            
            if ($row['is_array'] == TRUE)
            {
                $this->_field_data[$field]['postdata'] = $this->_reduce_array($this->_globals, $row['keys']);
            }
            else
            {
                if (isset($this->_globals[$field]) AND $this->_globals[$field] != '')
                {
                    $this->_field_data[$field]['postdata'] = $this->_globals[$field];
                }
            }
        
            $this->_execute($row, explode('|', $row['rules']), $this->_field_data[$field]['postdata']);        
        }
        
        // Did we end up with any errors?
        $total_errors = count($this->_error_array);

        if ($total_errors > 0)
        {
            $this->_safe_form_data = TRUE;
        }

        // Now we need to re-set the POST data with the new, processed data
        $this->_reset_post_array();
        
        // No errors, validation passes!
        if ($total_errors == 0)
        {
            return TRUE;
        }

        // Validation fails
        return FALSE;
    }

    // --------------------------------------------------------------------
    
    /**
     * Traverse a multidimensional $this->_globals array index until the data is found
     *
     * @access   private
     * @param    array
     * @param    array
     * @param    integer
     * @return   mixed
     */        
    private function _reduce_array($array, $keys, $i = 0)
    {
        if (is_array($array))
        {
            if (isset($keys[$i]))
            {
                if (isset($array[$keys[$i]]))
                {
                    $array = $this->_reduce_array($array[$keys[$i]], $keys, ($i+1));
                }
                else
                {
                    return NULL;
                }
            }
            else
            {
                return $array;
            }
        }
    
        return $array;
    }

    // --------------------------------------------------------------------
    
    /**
     * Re-populate the _POST array with our finalized and processed data
     *
     * @access    private
     * @return    null
     */        
    private function _reset_post_array()
    {
        foreach ($this->_field_data as $field => $row)
        {
            if ( ! is_null($row['postdata']))
            {
                if ($row['is_array'] == FALSE)
                {
                    if (isset($this->_globals[$row['field']]))
                    {
                        $this->_globals[$row['field']] = $this->prep_for_form($row['postdata']);
                    }
                }
                else
                {
                    // start with a reference
                    $post_ref =& $this->_globals;
                    
                    // before we assign values, make a reference to the right POST key
                    if (count($row['keys']) == 1)
                    {
                        $post_ref =& $post_ref[current($row['keys'])];
                    }
                    else
                    {
                        foreach ($row['keys'] as $val)
                        {
                            $post_ref =& $post_ref[$val];
                        }
                    }

                    if (is_array($row['postdata']))
                    {
                        $array = array();
                        foreach ($row['postdata'] as $k => $v)
                        {
                            $array[$k] = $this->prep_for_form($v);
                        }

                        $post_ref = $array;
                    }
                    else
                    {
                        $post_ref = $this->prep_for_form($row['postdata']);
                    }
                }
            }
        }
    }

    // --------------------------------------------------------------------
    
    /**
     * Executes the Validation routines
     *
     * @access   private
     * @param    array
     * @param    array
     * @param    mixed
     * @param    integer
     * @return   mixed
     */    
    private function _execute($row, $rules, $postdata = NULL, $cycles = 0)
    {
        // If the $this->_globals data is an array we will run a recursive call
        if (is_array($postdata))
        { 
            foreach ($postdata as $key => $val)
            {
                $this->_execute($row, $rules, $val, $cycles);
                $cycles++;
            }
            
            return;
        }
        
        // --------------------------------------------------------------------

        // If the field is blank, but NOT required, no further tests are necessary
        $callback = FALSE;
        if ( ! in_array('required', $rules) AND is_null($postdata))
        {
            // Before we bail out, does the rule contain a callback?
            if (preg_match("/(callback_\w+)/", implode(' ', $rules), $match))
            {
                $callback = TRUE;
                $rules = (array('1' => $match[1]));
            }
            else
            {
                return;
            }
        }

        // --------------------------------------------------------------------
        
        // Isset Test. Typically this rule will only apply to checkboxes.
        if (is_null($postdata) AND $callback == FALSE)
        {
            if (in_array('isset', $rules, TRUE) OR in_array('required', $rules))
            {
                // Set the message type
                $type = (in_array('required', $rules)) ? 'required' : 'isset';
            
                if ( ! isset($this->_error_messages[$type]))
                {
                    if (FALSE === ($line = lang($type)))
                    {
                        $line = 'The field was not set';
                    }                            
                }
                else
                {
                    $line = $this->_error_messages[$type];
                }
                
                // Build the error message
                $message = sprintf($line, $this->_translate_fieldname($row['label']));

                // Save the error message
                $this->_field_data[$row['field']]['error'] = $message;
                
                if ( ! isset($this->_error_array[$row['field']]))
                {
                    $this->_error_array[$row['field']] = $message;
                }
            }
                    
            return;
        }

        // --------------------------------------------------------------------

        // Cycle through each rule and run it
        foreach ($rules As $rule)
        {
            $_in_array = FALSE;
            
            // We set the $postdata variable with the current data in our master array so that
            // each cycle of the loop is dealing with the processed data from the last cycle
            if ($row['is_array'] == TRUE AND is_array($this->_field_data[$row['field']]['postdata']))
            {
                // We shouldn't need this safety, but just in case there isn't an array index
                // associated with this cycle we'll bail out
                if ( ! isset($this->_field_data[$row['field']]['postdata'][$cycles]))
                {
                    continue;
                }
            
                $postdata = $this->_field_data[$row['field']]['postdata'][$cycles];
                $_in_array = TRUE;
            }
            else
            {
                $postdata = $this->_field_data[$row['field']]['postdata'];
            }

            // Hmc callback
            // --------------------------------------------------------------------
            // Is the rule hmvc callback ?
            
            $hmvc_callback = FALSE;             
            $callback      = FALSE;      
            if (substr($rule, 0, 16) == 'callback_request')
            {
                $rule = substr($rule, 9);
                $hmvc_callback = TRUE;
            }
            else
            {
                if (substr($rule, 0, 9) == 'callback_')  // Is the rule a callback? 
                {
                    $rule = substr($rule, 9);
                    $callback = TRUE;
                }
            }
            
            // --------------------------------------------------------------------
            
            // Strip the parameter (if exists) from the rule
            // Rules can contain parameters: max_len[5], callback_request[get][/module/class/method]
            $param = FALSE;
            if (preg_match_all("/(.*?)\[(.*?)\]/", $rule, $matches))
            {
                $rule    = $matches[1][0];
                $param   = $matches[2][0];
                $second_param = (isset($matches[2][1])) ? $matches[2][1] : '';
            }
            
            
            if($hmvc_callback === TRUE) //  Call the hmvc function
            {
                loader::helper('ob/request');
                
                $result = FALSE;
                
                if($param !== FALSE)
                {
                    $response = request($param, $second_param)->exec();
                    
                    if($response === '1' || strtolower($response) === 'true')
                    {
                        $result = TRUE;
                    }
                }
                
                // Re-assign the result to the master data array
                if ($_in_array == TRUE)
                {
                    $this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
                }
                else
                {
                    $this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
                }
                                
            }
            elseif ($callback === TRUE)  // Call the function that corresponds to the rule
            {
                if(is_object($this->_callback_object))  // This is for VM model class
                {                                       // we set VM object as callback
                    $this->_this = $this->_callback_object;
                }
                else
                {
                    $this->_this = this();
                }
                
                if ( ! method_exists($this->_this, $rule))
                {         
                    continue;
                }
                
                // print_r(this()->db->ar_where);
                
                // Run the function and grab the result
                $result = $this->_this->$rule($postdata, $param);

                // Re-assign the result to the master data array
                if ($_in_array == TRUE)
                {
                    $this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
                }
                else
                {
                    $this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
                }
            
                // If the field isn't required and we just processed a callback we'll move on...
                if ( ! in_array('required', $rules, TRUE) AND $result !== FALSE)
                {
                    continue;
                }
            }
            else
            {                
                if ( ! method_exists($this, $rule))
                {
                    // If our own wrapper function doesn't exist we see if a native PHP function does. 
                    // Users can use any native PHP function call that has one param.
                    if (function_exists($rule))
                    {
                        $result = $rule($postdata);
                                            
                        if ($_in_array == TRUE)
                        {
                            $this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
                        }
                        else
                        {
                            $this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
                        }
                    }
                                        
                    continue;
                }

                $result = $this->$rule($postdata, $param);

                if ($_in_array == TRUE)
                {
                    $this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
                }
                else
                {
                    $this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
                }
            }
                            
            // Did the rule test negatively?  If so, grab the error.
            if ($result === FALSE)
            {            
                if ( ! isset($this->_error_messages[$rule]))
                {
                    if (FALSE === ($line = lang($rule)))
                    {
                        $line = 'Unable to access an error message corresponding to your field name.';
                    }                        
                }
                else
                {
                    $line = $this->_error_messages[$rule];
                }
                
                // Is the parameter we are inserting into the error message the name
                // of another field?  If so we need to grab its "field label"
                if (isset($this->_field_data[$param]) AND isset($this->_field_data[$param]['label']))
                {
                    $param = $this->_translate_fieldname($this->_field_data[$param]['label']);
                }
                
                // Build the error message
                $message = sprintf($line, $this->_translate_fieldname($row['label']), $param);

                // Save the error message
                $this->_field_data[$row['field']]['error'] = $message;
                
                if ( ! isset($this->_error_array[$row['field']]))
                {
                    $this->_error_array[$row['field']] = $message;
                }
                
                return;
            }

        }
    }

    // --------------------------------------------------------------------
    
    /**
     * Translate a field name
     *
     * @access   private
     * @param    string    the field name
     * @return   string
     */    
    private function _translate_fieldname($fieldname)
    {
        // Do we need to translate the field name?
        // We look for the prefix lang: to determine this
        if (substr($fieldname, 0, 5) == 'lang:')
        {
            // Grab the variable
            $line = substr($fieldname, 5);            
            
            // Were we able to translate the field name?  If not we use $line
            if (FALSE === ($fieldname = lang($line)))
            {
                return $line;
            }
        }

        return $fieldname;
    }

    // --------------------------------------------------------------------
    
    /**
     * Get the value from a form
     *
     * Permits you to repopulate a form field with the value it was submitted
     * with, or, if that value doesn't exist, with the default
     *
     * @access   public
     * @param    string    the field name
     * @param    string
     * @return   void
     */    
    public function set_value($field = '', $default = '')
    {
        if ( ! isset($this->_field_data[$field]))
        {
            return $default;
        }
        
        if(isset($this->_field_data[$field]['postdata']))
        { 
            return $this->_field_data[$field]['postdata'];
        } 
        elseif(isset($_REQUEST[$field]))
        {
            return $_REQUEST[$field];
        }
        
        return;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Set Select
     *
     * Enables pull-down lists to be set to the value the user
     * selected in the event of an error
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   string
     */    
    public function set_select($field = '', $value = '', $default = FALSE)
    {        
        if ( ! isset($this->_field_data[$field]) OR ! isset($this->_field_data[$field]['postdata']))
        {
            if ($default === TRUE AND count($this->_field_data) === 0)
            {
                return ' selected="selected"';
            }
            return '';
        }
    
        $field = $this->_field_data[$field]['postdata'];
        
        if (is_array($field))
        {
            if ( ! in_array($value, $field))
            {
                return '';
            }
        }
        else
        {
            if (($field == '' OR $value == '') OR ($field != $value))
            {
                return '';
            }
        }
            
        return ' selected="selected"';
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Set Radio
     *
     * Enables radio buttons to be set to the value the user
     * selected in the event of an error
     *
     * @access    public
     * @param    string
     * @param    string
     * @return    string
     */    
    public function set_radio($field = '', $value = '', $default = FALSE)
    {
        if ( ! isset($this->_field_data[$field]) OR ! isset($this->_field_data[$field]['postdata']))
        {
            if ($default === TRUE AND count($this->_field_data) === 0)
            {
                return ' checked="checked"';
            }
            return '';
        }
    
        $field = $this->_field_data[$field]['postdata'];
        
        if (is_array($field))
        {
            if ( ! in_array($value, $field))
            {
                return '';
            }
        }
        else
        {
            if (($field == '' OR $value == '') OR ($field != $value))
            {
                return '';
            }
        }
            
        return ' checked="checked"';
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Set Checkbox
     *
     * Enables checkboxes to be set to the value the user
     * selected in the event of an error
     *
     * @access    public
     * @param    string
     * @param    string
     * @return    string
     */    
    public function set_checkbox($field = '', $value = '', $default = FALSE)
    {
        if ( ! isset($this->_field_data[$field]) OR ! isset($this->_field_data[$field]['postdata']))
        {
            if ($default === TRUE AND count($this->_field_data) === 0)
            {
                return ' checked="checked"';
            }
            return '';
        }
    
        $field = $this->_field_data[$field]['postdata'];
        
        if (is_array($field))
        {
            if ( ! in_array($value, $field))
            {
                return '';
            }
        }
        else
        {
            if (($field == '' OR $value == '') OR ($field != $value))
            {
                return '';
            }
        }
            
        return ' checked="checked"';
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Required
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function required($str)
    {
        if ( ! is_array($str))
        {
            return (trim($str) == '') ? FALSE : TRUE;
        }
        else
        {
            return ( ! empty($str));
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Match one field to another
     *
     * @access    public
     * @param    string
     * @param    field
     * @return    bool
     */
    public function matches($str, $field)
    {
        if ( ! isset($this->_globals[$field]))
        {
            return FALSE;                
        }
        
        $field = $this->_globals[$field];

        return ($str !== $field) ? FALSE : TRUE;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Minimum length
     *
     * @access    public
     * @param    string
     * @param    value
     * @return    bool
     */    
    public function min_len($str, $val)
    {
        if (preg_match("/[^0-9]/", $val))
        {
            return FALSE;
        }
        
        return (mb_strlen($str) < $val) ? FALSE : TRUE;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Max length
     *
     * @access    public
     * @param    string
     * @param    value
     * @return    bool
     */    
    public function max_len($str, $val)
    {
        if (preg_match("/[^0-9]/", $val))
        {
            return FALSE;
        }

        return (mb_strlen($str) > $val) ? FALSE : TRUE;        
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Exact length
     *
     * @access   public
     * @param    string
     * @param    value
     * @return   bool
     */    
    public function exact_len($str, $val)
    {
        if (preg_match("/[^0-9]/", $val))
        {
            return FALSE;
        }

        return (mb_strlen($str) != $val) ? FALSE : TRUE;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Valid Email
     *
     * @access   public
     * @param    string
     * @return   bool
     */    
    public function valid_email($str)
    {
        return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }

    // --------------------------------------------------------------------
    
    /**
     * Valid Email + DNS Check
     *
     * @access   public
     * @param    string
     * @return   bool
     */
    public function valid_email_dns($str)
    {
        if($this->valid_email($str) === TRUE)
        {
            list($username,$domain) = explode('@',$str);
            
            if( ! checkdnsrr($domain,'MX'))
            {
                return FALSE;
            }

            return TRUE;
        }

        return FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * Valid Emails
     *
     * @access   public
     * @param    string
     * @return   bool
     */    
    public function valid_emails($str, $dns_check = '')
    {
        $dns_check = strtoupper($dns_check);
        
        $dns = '';
        if($dns_check == 'TRUE' OR $dns_check == 1)
        {
            $dns = '_dns';
        }
        
        if (strpos($str, ',') === FALSE)
        {
            return $this->{'valid_email'.$dns}(trim($str));
        }
        
        foreach(explode(',', $str) as $email)
        {
            if (trim($email) != '' && $this->{'valid_email'.$dns}(trim($email)) === FALSE)
            {
                return FALSE;
            }
        }
        
        return TRUE;
    }
    
    // --------------------------------------------------------------------
    
     /**
     * Validate Date
     *
     * @access   public
     * @param    string date
     * @param    string date_format
     * @return   string
     */
    public function valid_date($date, $format = 'mm-dd-yyyy')
    {        
        $format = strtoupper(trim($format));
        
        if(strpos($date, ' ') > 0 OR strlen($date) > 10 OR strlen($date) < 10) // well format and no time
        {
            return FALSE;
        }
        
        // get the date symbols.
        $date_array   = preg_split('/[\w]+/', $date, -1, PREG_SPLIT_NO_EMPTY);
        $format_array = preg_split('/[\w]+/', $format, -1, PREG_SPLIT_NO_EMPTY);

        if( ! isset($date_array[0]) AND ! isset($format_array[0]))
        {
            return FALSE;
        }
        
        $format = str_replace($format_array[0], '.', $format);

        switch ($format)   // Validate the right date format.
        {
            case 'YYYY.MM.DD':

                list($y, $m, $d) = explode($date_array[0], $date);     

                break;

            case 'DD.MM.YYYY':

                list($d, $m, $y) = explode($date_array[0], $date);   

                break;

            case 'MM.DD.YYYY':

                list($m, $d, $y) = explode($date_array[0], $date);  

                break;
        }


        return checkdate($m, $d, sprintf('%04u', $y));
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Validate IP Address
     *
     * @access   public
     * @param    string
     * @return   string
     */
    public function valid_ip($ip)
    {
        return i_valid_ip($ip);
    }

    // --------------------------------------------------------------------
    
    /**
     * Alpha
     *
     * @access   public
     * @param    string
     * @return   bool
     */        
    public function alpha($str)
    {
        return ( ! preg_match("/^([a-z])+$/i", $str)) ? FALSE : TRUE;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Alpha-numeric
     *
     * @access   public
     * @param    string
     * @return   bool
     */    
    public function alpha_numeric($str)
    {
        return ( ! preg_match("/^([a-z0-9])+$/i", $str)) ? FALSE : TRUE;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Alpha-numeric with underscores and dashes
     *
     * @access   public
     * @param    string
     * @return   bool
     */    
    public function alpha_dash($str)
    {
        return ( ! preg_match("/^([-a-z0-9_-])+$/i", $str)) ? FALSE : TRUE;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Numeric
     *
     * @access    public
     * @param    string
     * @return    bool
     */    
    public function numeric($str)
    {
        return (bool)preg_match( '/^[\-+]?[0-9]*\.?[0-9]+$/', $str);
    }

    // --------------------------------------------------------------------

    /**
     * Is Numeric
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function is_numeric($str)
    {
        return ( ! is_numeric($str)) ? FALSE : TRUE;
    } 

    // --------------------------------------------------------------------
    
    /**
     * Integer
     *
     * @access    public
     * @param    string
     * @return    bool
     */    
    public function integer($str)
    {
        return (bool)preg_match( '/^[\-+]?[0-9]+$/', $str);
    }
    
    // --------------------------------------------------------------------

    /**
     * Is a Natural number  (0,1,2,3, etc.)
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function is_natural($str)
    {   
           return (bool)preg_match( '/^[0-9]+$/', $str);
    }

    // --------------------------------------------------------------------

    /**
     * Is a Natural number, but not a zero  (1,2,3, etc.)
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function is_natural_no_zero($str)
    {
        if ( ! preg_match( '/^[0-9]+$/', $str))
        {
            return FALSE;
        }
        
        if ($str == 0)
        {
            return FALSE;
        }
    
           return TRUE;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Valid Base64
     *
     * Tests a string for characters outside of the Base64 alphabet
     * as defined by RFC 2045 http://www.faqs.org/rfcs/rfc2045
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function valid_base64($str)
    {
        return (bool) ! preg_match('/[^a-zA-Z0-9\/\+=]/', $str);
    }

    // --------------------------------------------------------------------

    /**
     * Check string is contain
     * space
     *
     * @param string $str
     * @return bool
     */
    public function no_space($str)
    {
       return (preg_match("#\s#", $str)) ? FALSE : TRUE;
    } 

    // --------------------------------------------------------------------
    
    /**
     * Prep data for form
     *
     * This function allows HTML to be safely shown in a form.
     * Special characters are converted.
     *
     * @access    public
     * @param    string
     * @return    string
     */
    public function prep_for_form($data = '')
    {
        if (is_array($data))
        {
            foreach ($data as $key => $val)
            {
                $data[$key] = $this->prep_for_form($val);
            }
            
            return $data;
        }
        
        if ($this->_safe_form_data == FALSE OR $data === '')
        {
            return $data;
        }

        return str_replace(array("'", '"', '<', '>'), array("&#39;", "&quot;", '&lt;', '&gt;'), stripslashes($data));
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Prep URL
     *
     * @access    public
     * @param    string
     * @return    string
     */    
    public function prep_url($str = '')
    {
        if ($str == 'http://' OR $str == '')
        {
            return '';
        }
        
        if (substr($str, 0, 7) != 'http://' && substr($str, 0, 8) != 'https://')
        {
            $str = 'http://'.$str;
        }
        
        return $str;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Strip Image Tags
     *
     * @access    public
     * @param    string
     * @return    string
     */    
    public function strip_image_tags($str)
    {
        loader::helper('ob/security');
        
        return strip_image_tags($str);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * XSS Clean
     *
     * @access    public
     * @param    string
     * @return    string
     */    
    public function xss_clean($str)
    {
        loader::helper('ob/security');
        
        return xss_clean($str);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Convert PHP tags to entities
     *
     * @access   public
     * @param    string
     * @return   string
     */    
    public function encode_php_tags($str)
    {
        return str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
    }

}
// END Form Validation Class

/* End of file Validator.php */
/* Location: ./obullo/libraries/Validator.php */