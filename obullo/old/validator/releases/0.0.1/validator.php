<?php

/**
 * Validator Class
 *
 * @package       packages
 * @subpackage    validator
 * @category      validation
 * @link        
 */
Class Validator 
{
    public $_field_data         = array();    
    public $_config_rules       = array();
    public $_error_array        = array();
    public $errorMessages     = array();    
    public $_error_prefix       = '';
    public $_error_suffix       = '';
    public $_error_string       = '';
    public $_safe_form_data     = false;
    public $_callback_object    = null;
    public $_validation         = false;

    public function __construct($rules = array())
    {    
        global $config, $logger;

        $this->_config_rules = $rules;  // Validation rules can be stored in a config file.

        mb_internal_encoding($config['charset']);

        if( ! isset(getInstance()->validator))
        {
            getInstance()->validator = $this;
        }

        $logger->debug('Validator Class Initialized');
    }

    // --------------------------------------------------------------------
    
    /**
     * Call functions when they called to consume less memory.
     *
     * @access  private
     * @param  string $method   
     * @param  array $arguments 
     * @return void            
     */
    public function __call($method, $arguments)
    {
        global $packages;

        $file = PACKAGES .'validator'. DS .'releases'. DS .$packages['dependencies']['validator']['version']. DS .'src'. DS .mb_strtolower($method). EXT;

        if (file_exists($file)) {

            if ( ! function_exists('Validator\Src\\'.$method)) {
                include $file;
            }

            return call_user_func_array('Validator\Src\\'.$method, $arguments);
        }
    }

    // --------------------------------------------------------------------
    
    /**
     * Clear object variables 
     * 
     * @return void
     */
    public function clear()
    {
        $this->_field_data         = array();
        $this->_config_rules       = array();
        $this->_error_array        = array();
        $this->errorMessages     = array();
        $this->_error_prefix       = '';
        $this->_error_suffix       = '';
        $this->_error_string       = '';
        $this->_safe_form_data     = false;
        $this->_callback_object    = null;
        $this->_validation         = false;
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
        $this->{$key} = $val;
    }

    // --------------------------------------------------------------------
    
    /**
     * Create label automatically.
     * 
     * @param  string $field field name
     * @return string label
     */
    private function _createLabel($field)
    {
        $label = ucfirst($field);

        if(strpos($field, '_') > 0)
        {
            $words   = explode('_', strtolower($field));
            $ucwords = array_map('ucwords', $words);
            $label   = implode(' ', $ucwords);
        }

        return $label;
    }

    // --------------------------------------------------------------------

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
    public function setRules($field, $label = '', $rules = '')
    {        
        if (count($_REQUEST) == 0)   // No reason to set rules if we have no POST or GET data
        {
            return;
        }
    
        // If an array was passed via the first parameter instead of indidual string
        // values we cycle through it and recursively call this function.
        if (is_array($field))
        {
            foreach ($field as $row)
            {
                if ( ! isset($row['field']) OR ! isset($row['rules'])) //  we have a problem...
                {
                    continue;
                }

                // If the field label wasn't passed we use the field name
                $label = ( ! isset($row['label'])) ? $this->_createLabel($row['field']) : $row['label'];

                $this->setRules($row['field'], $label, trim($row['rules'], '|'));  // Here we go!

            }

            return;
        }

        if ( ! is_string($field) OR  ! is_string($rules) OR $field == '')  // No fields? Nothing to do...
        {
            return;
        }

        // If the field label wasn't passed we use the field name
        $label = ($label == '') ? $this->_createLabel($field) : $label;

        // Is the field name an array?  We test for the existence of a bracket "(" in
        // the field name to determine this.  If it is an array, we break it apart
        // into its components so that we can fetch the corresponding POST data later     

        if (strpos($field, '(') !== false AND preg_match_all('/\((.*?)\)/', $field, $matches))
        {    
            $x = explode('(', $field);

            // Note: Due to a bug in current() that affects some versions
            // of PHP we can not pass function call directly into it.
            
            $indexes[] = current($x);

            for ($i = 0; $i < count($matches['0']); $i++)
            {
                if ($matches['1'][$i] != '')
                {
                    $indexes[] = $matches['1'][$i];
                }
            }
            
            $is_array = true;
        }
        else
        {
            $indexes  = array();
            $is_array = false;        
        }
        
        // Build our master array        
        $this->_field_data[$field] = array(
                                            'field'                => $field, 
                                            'label'                => $label, 
                                            'rules'                => trim($rules,'|'),
                                            'is_array'            => $is_array,
                                            'keys'                => $indexes,
                                            'postdata'            => null,
                                            'error'                => ''
                                            );
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
    public function isValid($group = '')
    {
        global $logger;

        if (count($_REQUEST) == 0)  // Do we even have any data to process?  Mm?
        {
            return false;
        }
    
        if (count($this->_field_data) == 0)         // Does the _field_data array containing the validation rules exist ?
        {                                           // If not, we look to see if they were assigned via a config file
            if (count($this->_config_rules) == 0)   // No validation rules ?  We're done...
            {
                $this->errorMessages['message'] = 'No validation rule is defined.';

                $logger->error($this->errorMessages['message']);

                return false;
            }
            
            $uriClass = getComponentInstance('uri');
            
            $uri = ($group == '') ? trim($uriClass->getRoutedUriString(), '/') : $group; // Is there a validation rule for the particular URI being accessed?
            
            if ($uri != '' AND isset($this->_config_rules[$uri]))
            {
                $this->setRules($this->_config_rules[$uri]);
            }
            else
            {
                $this->setRules($this->_config_rules);
            }

            if (sizeof($this->_field_data) == 0)  // We're we able to set the rules correctly ?
            {
                $this->errorMessages['message'] = 'Unable to find validation rules';

                $logger->error($this->errorMessages['message']);

                return false;
            }
        }

        getInstance()->translator->load('validator');     // Load Obullo language file.

        // Cycle through the rules for each field, match the 
        // corresponding $_REQUEST item and test for errors
        
        foreach ($this->_field_data as $field => $row)
        {        
            // Fetch the data from the corresponding $_REQUEST array and cache it in the _field_data array.
            // Depending on whether the field name is an array or a string will determine where we get it from.
            
            if ($row['is_array'] == true)
            {
                $this->_field_data[$field]['postdata'] = $this->_reduceArray($_REQUEST, $row['keys']);
            }
            else
            {
                if (isset($_REQUEST[$field]) AND $_REQUEST[$field] != '')
                {
                    $this->_field_data[$field]['postdata'] = $_REQUEST[$field];
                }
            }

            $this->_execute($row, explode('|', $row['rules']), $this->_field_data[$field]['postdata']);        
        }
        
        $total_errors = sizeof($this->_error_array);         // Did we end up with any errors?

        if ($total_errors > 0)
        {
            $this->_safe_form_data = true;
        }

        $this->_resetPostArray();   // Now we need to re-set the POST data with the new, processed data

        if ($total_errors == 0)     // No errors, validation passes !
        {
            $this->_validation = true;

            return true;
        }

        return false;         // Validation fails
    }

    // --------------------------------------------------------------------
    
    /**
     * Traverse a multidimensional $_REQUEST array index until the data is found
     *
     * @access   private
     * @param    array
     * @param    array
     * @param    integer
     * @return   mixed
     */        
    private function _reduceArray($array, $keys, $i = 0)
    {
        if (is_array($array))
        {
            if (isset($keys[$i]))
            {
                if (isset($array[$keys[$i]]))
                {
                    $array = $this->_reduceArray($array[$keys[$i]], $keys, ($i+1));
                }
                else
                {
                    return null;
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
    private function _resetPostArray()
    {
        foreach ($this->_field_data as $field => $row)
        {
            if ( ! is_null($row['postdata']))
            {
                if ($row['is_array'] == false)
                {
                    if (isset($_REQUEST[$row['field']]))
                    {
                        $_REQUEST[$row['field']] = $this->prepForForm($row['postdata']);
                    }
                }
                else
                {
                    $post_ref =& $_REQUEST;   // start with a reference

                    if (count($row['keys']) == 1)  // before we assign values, make a reference to the right POST key
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
                            $array[$k] = $this->prepForForm($v);
                        }

                        $post_ref = $array;
                    }
                    else
                    {
                        $post_ref = $this->prepForForm($row['postdata']);
                    }
                }
            }
        }
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
    public function prepForForm($data = '')
    {
        if (is_array($data))
        {
            foreach ($data as $key => $val)
            {
                $data[$key] = $this->prepForForm($val);
            }
            
            return $data;
        }
        
        if ($this->_safe_form_data == false OR $data === '')
        {
            return $data;
        }

        return str_replace(array("'", '"', '<', '>'), array("&#39;", "&quot;", '&lt;', '&gt;'), stripslashes($data));
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
    private function _execute($row, $rules, $postdata = null, $cycles = 0)
    {
        if (is_array($postdata))         // If the $_REQUEST data is an array we will run a recursive call
        { 
            foreach ($postdata as $key => $val)
            {
                $this->_execute($row, $rules, $val, $cycles);
                $cycles++;
            }
            return;
        }

        $callback = false;         // If the field is blank, but NOT required, no further tests are necessary
        if ( ! in_array('required', $rules) AND is_null($postdata))
        {
            if (preg_match("/(callback_\w+)/", implode(' ', $rules), $match))  // Before we bail out, does the rule contain a callback?
            {
                $callback = true;
                $rules = (array('1' => $match[1]));
            }
            else
            {
                return;
            }
        }

        if (is_null($postdata) AND $callback == false)         // Isset Test. Typically this rule will only apply to checkboxes.
        {   
            if (in_array('isset', $rules, true) OR in_array('required', $rules))
            {
                $type = (in_array('required', $rules)) ? 'required' : 'isset';  // Set the message type

                if ( ! isset($this->errorMessages[$type]))
                {
                    $line = translate($type);  

                    if (hasTranslate($type) == false)
                    {
                        $line = 'The field was not set';
                    }
                }
                else
                {
                    $line = $this->errorMessages[$type];
                }

                $message = sprintf($line, $this->_translateFieldname($row['label']));    // Build the error message
                $this->_field_data[$row['field']]['error'] = $message;                 // Save the error message
                
                if ( ! isset($this->_error_array[$row['field']]))
                {
                    $this->_error_array[$row['field']] = $message;
                }
            }
                    
            return;
        }

        // --------------------------------------------------------------------

        foreach ($rules as $rule)         // Cycle through each rule and run it
        {
            $_in_array = false;             // We set the $postdata variable with the current data in our master array so that
                                            // each cycle of the loop is dealing with the processed data from the last cycle
            if ($row['is_array'] == true AND is_array($this->_field_data[$row['field']]['postdata']))
            {
                if ( ! isset($this->_field_data[$row['field']]['postdata'][$cycles]))  // We shouldn't need this safety, 
                {                                                                      // but just in case there isn't an array index
                    continue;                                                          // associated with this cycle we'll bail out
                }
            
                $postdata = $this->_field_data[$row['field']]['postdata'][$cycles];
                $_in_array = true;
            }
            else
            {
                $postdata = $this->_field_data[$row['field']]['postdata'];
            }
            
            $callback = false;
            if (substr($rule, 0, 9) == 'callback_')  // Is the rule a callback? 
            {
                // $rule = substr($rule, 9);
                $callback = true;
            }
        
            $param = false;
            if (preg_match_all("/(.*?)\((.*?)\)/", $rule, $matches))  // Strip the parameter (if exists) from the rule
            {                                                         // Rules can contain parameters: minLen(5),
                $rule    = $matches[1][0];
                $param   = $matches[2][0];
                $second_param = (isset($matches[2][1])) ? $matches[2][1] : '';
            }
            
            if ($callback === true)  // Call the function that corresponds to the rule
            {
                $this->_this  = is_object($this->_callback_object) ? $this->_callback_object : getInstance();            
                $classMethods = get_class_methods($this->_this);

                if(isset($this->_this->_callback_functions)) // is the Form _callback functions exists ?
                {
                    $classMethods = array_merge($classMethods, array_keys($this->_this->_callback_functions));
                }
                
                if ( ! in_array($rule, $classMethods)) // Check method exists in callback object.
                {  
                    continue;
                }
                
                $result = $this->_this->$rule($postdata, $param);  // Run the function and grab the result

                if ($_in_array == true)      // Re-assign the result to the master data array
                {
                    $this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
                }
                else
                {
                    $this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
                }
                
                if ( ! in_array('required', $rules, true) AND $result !== false) // If the field isn't required and we just processed a callback we'll move on
                {
                    continue;
                }
            }
            else
            {
                global $packages, $logger;

                $file = PACKAGES .'validator'. DS .'releases'. DS .$packages['dependencies']['validator']['version']. DS .'src'. DS .strtolower($rule). EXT;

                if( ! file_exists($file))
                {
                    if (function_exists($rule))         // If our own wrapper function doesn't exist we see if a native PHP function does. 
                    {                                   // Users can use any native PHP function call that has one param.
                        $result = $rule($postdata);

                        if ($_in_array == true)
                        {
                            $this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
                        }
                        else
                        {
                            $this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
                        }
                    }
                    else
                    {
                        $this->errorMessages['message'] = 'The '.$rule.' is not a valid rule, if you have new validation method do pull request on the github.';

                        $logger->error($this->errorMessages['message']);
                    } 
                    
                    continue;                   
                }

                ####### Call Rule ###########
                
                if( ! function_exists('Validator\Src\\'.$rule))
                {
                    global $packages;

                    include PACKAGES .'validator'. DS .'releases'. DS .$packages['dependencies']['validator']['version']. DS .'src'. DS .strtolower($rule). EXT;
                }

                $result = call_user_func_array('Validator\Src\\'.$rule, array($postdata, $param));

                ####### Call Rule ###########

                if ($_in_array == true)
                {
                    $this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
                }
                else
                {
                    $this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
                }
            }
                            
            if ($result === false)             // Did the rule test negatively?  If so, grab the error.
            {            
                if ( ! isset($this->errorMessages[$rule]))
                {
                    $line = translate($rule);

                    if (hasTranslate($rule) == false)
                    {
                        $line = ''; // Dont't show the translate message - Unable to translation access an error message corresponding to your field name.'
                    }                        
                }
                else
                {
                    $line = $this->errorMessages[$rule];
                }
                
                if (isset($this->_field_data[$param]) AND isset($this->_field_data[$param]['label']))  // Is the parameter we are inserting into the error message the name
                {                                                                                      // of another field?  If so we need to grab its "field label"
                    $param = $this->_translateFieldname($this->_field_data[$param]['label']);
                }
                
                $message = sprintf($line, $this->_translateFieldname($row['label']), $param); // Build the error message

                $this->_field_data[$row['field']]['error'] = $message;   // Save the error message
                
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
    private function _translateFieldname($fieldname)
    {
        if (substr($fieldname, 0, 10) == 'translate:') // Do we need to translate the field name? 
        {
            $line = substr($fieldname, 10);   // Grab the variable

            if (hasTranslate($line)) // Were we able to translate the field name? If not we use $line.
            {
                return translate($line);
            }
        }

        return $fieldname;
    }

    // --------------------------------------------------------------------
    
    /**
     * !! WARNING Don't move this function to 
     * /src folder because of Form class __call this function 
     * magically.
     * 
     * Set Error Message
     *
     * Lets users set their own error messages on the fly.  Note:  The key
     * name has to match the  function name that it corresponds to.
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   string
     */
    public function setMessage($key, $val = '')
    {
        $validator = getInstance()->validator;
        
        if ( ! is_array($key))
        {
            $key = array($key => $val);
        }
    
        $validator->errorMessages = array_merge($validator->errorMessages, $key);
    }

    // ------------------------------------------------------------------------
    
    /**
     * Set error(s) to form validator
     * 
     * @param mixed $key 
     * @param string $val 
     * @return type
     */
    public function setError($key, $val = '')
    {
        $validator = getInstance()->validator;

        if (is_array($key)) 
        {
            foreach ($key as $k => $v) 
            {
                $validator->_field_data[$k]['error'] = $v;
                $validator->_error_array[$k] = $v;
            }
        } 
        else
        {
            $validator->_field_data[$key]['error'] = $val;
            $validator->_error_array[$key] = $val;
        }
    }

}

// END Form Validator Class

/* End of file validator.php */
/* Location: ./packages/validator/releases/0.0.1/validator.php */