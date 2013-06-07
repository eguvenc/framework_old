<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         Obullo
 * @author          Obullo.com  
 * @subpackage      Obullo.libraries        
 * @copyright       Obullo Team
 * @license          
 * @since           Version 1.0
 * @filesource
 */ 
 
/**
 * Validation Model.
 *
 * @package         Obullo 
 * @subpackage      Obullo.core     
 * @category        Core Model
 * @version         0.1
 */                    

Class Vmodel extends Model {

    public $property   = array();  // User public variables, we set them in controller.
    public $errors     = array();  // Validation errors.
    public $values     = array();  // Filtered safe values.
    public $no_save    = array();  // No save fields, so save function will not save selected fields to database.
    public $validation = FALSE;    // If form validation success we set it to true.
    
    public $function   = array();
    
    public $where           = array();
    public $or_where        = array();
    public $where_in        = array();
    public $or_where_in     = array();
    public $where_not_in    = array();
    public $or_where_not_in = array();

    
    /**
    * Construct
    * 
    * @return void
    */
    public function __construct()
    {                
        parent::__construct();
        
        if( ! isset($this->settings['fields']) OR ! isset($this->settings['database'])) 
        {
            throw new Exception('Check your model it must be contain $settings[\'fields\'] and $settings[\'database\'] array.');
        }
        
        $db = $this->settings['database'];
                 
        if($db != '' AND $db != 'no')
        {
            $this->db = loader::database($db, TRUE); // Cannot assign by reference to overloaded object 
        }
                            
        if( ! isset($this->settings['table'])) // create random table name
        {
            $this->settings['table'] = 'unknown_'.rand();
        }
        
        ##### Reset validation data for multiple operations #######
        
        lib('ob/Validator')->clear();
        
        ##### Reset validation data for multiple operations #######
        
        log_me('debug', "Vmodel Class Initialized");
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Grab validator object and set validation
    * rules automatically.
    * 
    * @param  array $_GLOBALS can be $_POST, $_GET, $_FILES
    * @return boolean
    */
    private function validator($_GLOBALS = array(), $fields = array())
    {
        $validator = lib('ob/Validator', '', TRUE);
        $validator->clear();

        if(count($_GLOBALS) == 0)
        {
            $_GLOBALS == $_REQUEST;
        }

        if(count($fields) == 0)
        {
            $fields = $this->settings['fields'];
        }

        $validator->set('_globals', $_GLOBALS);
        $validator->set('_callback_object', $this);
    
        $table = $this->settings['table'];

        foreach($fields as $key => $val)
        {
            if(is_array($this->$key) AND isset($this->{$key}['rules'])) // reset validation rules
            {   
                $val['rules'] = $this->{$key}['rules'];
                $new_value    = (isset($this->{$key}['value'])) ? $this->{$key}['value'] : FALSE;
                
                if($new_value !== FALSE) 
                {
                    $this->{$key} = $new_value;
                }
            }

            if(is_array($val))
            {
                if(isset($val['rules']) AND $val['rules'] != '')
                {
                    $validator->set_rules($key, $val['label'], $val['rules']);
                }
            }
        }
        
        if($validator->run())   // Run validation
        {            
            foreach($fields as $key => $val)  // Set filtered values
            {
                $this->values[$table][$key] = $this->_set_value($key, $this->{$key});
            }
            
            $this->validation = TRUE;
            
            return TRUE;
        }
        else 
        {
            foreach($fields as $key => $val)  // Set validation errors..
            {
               if(isset($validator->_field_data[$key]['error']))
               {
                   $error = $validator->error($key, NULL, NULL);

                   if( ! empty($error))
                   {
                       $this->errors[$table][$key] = $error;
                   }
               }
              
               // Set filtered values
               $this->values[$table][$key] = $this->_set_value($key, $this->{$key});
            }
            
            $this->validation = FALSE;
            
            return FALSE;
        }
    }
    
    // --------------------------------------------------------------------

    /**
    * Validate the native GET or POST requests.
    * No insert, No update operations.
    *
    * @return bool
    */
    public function validate($fields = array())
    {
        lib('ob/Lang')->load('ob/vm');  // Load the language file
        
        $v_data = array();   // validation fields data
        $db_fields = $this->settings['fields'];
        
        if(count($fields) > 0)
        {
            unset($db_fields);
            
            foreach($fields as $f_key => $v)
            {
                $db_fields[$f_key] = $this->settings['fields'][$f_key];
            }
        }

        foreach($db_fields as $k => $v)
        {
            if($this->{$k} != '')
            {
                $v_data[$k] = $this->_set_value($k, $this->{$k});
            }
            else
            {
                $v_data[$k] = $this->_set_value($k, i_get_post($k));
            }
        }
        
        return $this->validator($v_data, $db_fields);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Check Validation is success ?
    *
    * @return boolean
    */
    public function validation()
    {
        return $this->validation;
    }
    
    // --------------------------------------------------------------------
   
    /**
    * Return validaton errors to current model.
     * 
    * @return array
    */
    public function errors($key = '')
    {
        if($key == 'transaction')
        {
            if(isset($this->errors[$this->item('table')]['transaction_error']))
            {
                return $this->errors[$this->item('table')]['transaction_error'];
            } 
            else 
            {
                return;
            }
        }
        
        if(isset($this->errors[$this->item('table')]))
        {
            if(isset($this->errors[$this->item('table')][$key]))
            {
                return $this->errors[$this->item('table')][$key];
            }
            
            if($key != '')
            {
                return;
            }
            
            return $this->errors[$this->item('table')];
        }
        
        if($key != '')
        {
            return;
        }
        
        return $this->errors;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Build Httpd GET friendly error query strings.
     * 
     * errors[user_password]=Wrond%20Password!&errors['user_email']=Wrong%20Email%20Address!
     * @return type 
     */
    public function build_query_errors()
    {
        $http_query['errors'] = array();
        if(isset($this->errors[$this->item('table')]))
        {
            foreach($this->errors[$this->item('table')] as $key => $val)
            {
                if(is_string($val))
                {
                    $http_query['errors'][$key] = $val;
                }
            }
        }
        
        return http_build_query($http_query);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Return filtered validation values for current model.
    * 
    * @param string $field return to filtered one item's value.
    * @return array
    */
    public function values($field = '')
    {
        if(isset($this->values[$this->item('table')]))
        {
            if(isset($this->values[$this->item('table')][$field]))
            {
                return $this->values[$this->item('table')][$field];
            }
            
            return $this->values[$this->item('table')];
        }
        
        return $this->values;
    }

    // --------------------------------------------------------------------

    /**
    * Set Custom Vmodel error for field.
    *
    * @param string $key or $field
    */
    public function set_error($key, $error)
    {
        $this->errors[$this->item('table')][$key] = $error;
        
        $fields = $this->settings['fields'];
        
        if(isset($fields[$key])) // set a validation error.
        {
            lib('ob/Validator')->_field_data[$key]['error'] = $error;
        }
    }

    // --------------------------------------------------------------------

    /**
    * Set Custom Vmodel function.
    *
    * @param string $key name of function
    */
    public function set_func($name, $val)
    {
        $this->function[$this->item('table')]['name'] = $name;
        $this->function[$this->item('table')]['val']  = $val;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Get the function name and values.
    *
    * @param string $type
    */
    public function get_func($type = 'name')
    {        
       if($type == '')
       {
           if(isset($this->function[$this->item('table')]['name']))
           {
               return TRUE;
           }
           
           return FALSE;
       }
       
       if(isset($this->function[$this->item('table')][$type]))
       {
           return $this->function[$this->item('table')][$type];
       }
       
       return FALSE;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Set value for field.
    *
    * @param string $key or $field
    */
    public function set_value($key, $value)
    {
        $this->values[$this->item('table')][$key] = $value;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Get Settings
    * 
    * @param mixed $item
    * @param mixed $index
    */
    public function item($item)
    {
        if(strpos($item, '['))
        {
            $index = explode('[', $item);
            $index_item = str_replace(']', '', $index[1]);
            
            if(isset($this->settings[$index[0]][$index_item]))
            {
                return $this->settings[$index[0]][$index_item];
            }
            
            return FALSE;
        }
        else
        {
            if(isset($this->settings[$item]))
            {
                return $this->settings[$item];
            }
            
            return FALSE;
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Set requested property
    * 
    * @param  string $key
    * @param  mixed  $val
    * @return void
    */
    public function __set($key, $val) 
    {
        $this->property[$key] = $val;
    }
    
    // --------------------------------------------------------------------
   
    /**
    * Get requested property
    * 
    * @param  string $property_name
    * @return mixed
    */
    public function __get($key) 
    {
        if(isset($this->property[$key])) 
        {
            return($this->property[$key]);
        } 
        else 
        {
            return(NULL);
        }
    }
    
    // --------------------------------------------------------------------

    /**
    * Some times we don't want save some fields or that 
    * the fields which we haven't got in the db tables we have to validate them.
    * To overcome to this problem we use the $model->no_save(); function.
    * 
    * @param type $key
    */
    public function no_save($key)
    {
        $this->no_save[$key] = $key;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * We build where statements before the
     * delete and save operations.
     * 
     * @param string $key
     * @param string $val 
     */
    public function where($key, $val)
    {
        $this->{$key} = $val;  // set data for validation

        $this->where[$key] = $val;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * We build where statements before the
     * delete and save operations.
     * 
     * @param string $key
     * @param string $val 
     */
    public function or_where($key = '', $val = '')
    {
        $this->{$key} = $val;  // set data for validation
        
        $this->or_where[$key] = $val;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * We build where statements before the
     * delete and save operations.
     * 
     * @param string $key
     * @param array $val 
     */
    public function where_in($key = '', $val = array())
    {
        foreach($val as $v)
        {
            $this->{$key} = $v;  
        }
        
        $this->where_in[$key] = $val;
    }
    
    // --------------------------------------------------------------------
    
     /**
     * We build where statements before the
     * delete and save operations.
     * 
     * @param string $key
     * @param array $val 
     */
    public function or_where_in($key = '', $val = array())
    {
        foreach($val as $v)
        {
            $this->{$key} = $v;  
        }

        $this->or_where_in[$key] = $val;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * We build where statements before the
     * delete and save operations.
     * 
     * @param string $key
     * @param array $val 
     */
    public function where_not_in($key = '', $val = array())
    {
        foreach($val as $v)
        {
            $this->{$key} = $v;  
        }
        
        $this->where_not_in[$key] = $val;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * We build where statements before the
     * delete and save operations.
     * 
     * @param string $key
     * @param array $val 
     */
    public function or_where_not_in($key = '', $val = array())
    {
        foreach($val as $v)
        {
            $this->{$key} = $v;  
        }
        
        $this->or_where_not_in[$key] = $val;
    }
    
    // --------------------------------------------------------------------

    /**
    * Do update if ID exists otherwise
    * do insert.
    * 
    * @return  FALSE | Integer
    */
    public function save()
    {
        lib('ob/Lang')->load('ob/vm');
                   
        $v_data = array();   // validation fields data
        $s_data = array();   // mysql insert / update fields data
        $table  = $this->item('table');
        $id     = ($this->item('primary_key') !== FALSE) ? $this->item('primary_key') : 'id';
        
        if(count($this->no_save) > 0)
        {
            foreach($this->no_save as $k)
            {
                unset($this->settings['fields'][$k]);
            }
        }

        $has_rules = FALSE;
        
        foreach($this->settings['fields'] as $k => $v)
        {
            if(strpos($k, '[]') > 0)  // remove multiple key names from save key ..
            {
                $k = str_replace('[]', '', $k);
            }
            
            if(isset($this->settings['fields'][$k]['rules']))  // validation used or not
            {
                $has_rules = TRUE;
            }
            
            if($this->{$k} != '')
            {
                $v_data[$k] = $this->_set_value($k, $this->property[$k]);
            }
            
            if($this->{$k} != '')
            {
                if(isset($this->settings['fields'][$k]['func']))  // functions ..
                {
                    $function_string = trim($this->settings['fields'][$k]['func'], '|');

                    if(strpos($function_string, '|') > 0)
                    {
                       $functions = explode('|', $this->settings['fields'][$k]['func']);
                    }
                    else
                    {
                       $functions = array($function_string);
                    }

                    foreach($functions as $name)
                    {
                        if(is_array($this->{$k}) AND isset($this->{$k}['value'])) // set custom var rules
                        {
                            $s_data[$k] = $this->{'_'.$name}($this->{$k}['value']);
                        }
                        else
                        {
                            $s_data[$k] = $this->{'_'.$name}($v_data[$k]);
                        }
                    }
                }
                else
                {
                    if(is_array($this->{$k}) AND isset($this->{$k}['value'])) // set custom var rules
                    {
                        $s_data[$k] = $this->{$k}['value'];
                    }
                    else
                    {
                        $s_data[$k] = $v_data[$k];
                    }
                }
            }
            else
            {
                if(isset($this->property[$k])) // anyway send null data
                {
                    $s_data[$k] = '';
                }

                $v_data[$k] = $this->_set_value($k, i_get_post($k));
            }
            
            // ************ do not save if field selected via where() function
            
            if(isset($this->where[$k]) OR isset($this->where_in[$k]))
            {
                unset($s_data[$k]);
            }
            
        } // end foreach.

        if($has_rules)  // if we have validation rules ..
        {
            $validator = $this->validator($v_data);  // do validation
        }
        else
        {
            $validator = TRUE;  // don't do validation
        }
        
        if($validator)  // if validation success !
        {
            if($this->{$id} != '' OR count($this->where) > 0 OR count($this->where_in) > 0)  // if isset ID do update ..
            {   
                unset($s_data[$id]);
                
                if($this->{$id} != '' AND ! isset($this->where[$id]) AND ! isset($this->where_in[$id]))
                {
                    $this->where[$id] = $this->{$id};  // store where statetements.
                }
                
                try {

                    $this->db->transaction(); // begin the transaction

                    $this->_before_save();
                                    
                    $this->_compile_select();                
                    $this->errors[$table]['affected_rows'] = $this->db->update($table, $s_data);
                    
                    $this->_after_save();
                    
                    $this->db->commit();    // commit the transaction
                    
                    $this->errors[$table]['success'] = 1;
                    $this->errors[$table]['msg']     = lang('vm_update_success');
                    
                    $this->clear();    // reset validator data

                    return TRUE;

                } catch(Exception $e)
                {
                    $this->db->rollback();       // roll back the transaction if we fail

                    $this->errors[$table]['success'] = 0;
                    $this->errors[$table]['msg']     = lang('vm_update_fail');
                    $this->errors[$table]['transaction_error'] = $e->getMessage();
                    
                    $this->clear();    // reset validator data

                    return FALSE;
                }
                
            }
            else   // ELSE do insert ..
            {
                try {

                    $this->db->transaction(); // begin the transaction
                    
                    $this->_before_save();
                     
                    $this->_compile_select();    
                    $this->errors[$table]['affected_rows'] = $this->db->insert($table, $s_data);
                    $this->values[$table][$id] = $this->db->insert_id();  // add last inserted id.

                    $this->_after_save();
                    
                    $this->db->commit();    // commit the transaction
                    
                    $this->errors[$table]['success'] = 1;
                    $this->errors[$table]['msg']     = lang('vm_insert_success');
                    
                    $this->clear();    // reset validator data  // reset validator data

                    return TRUE;

                } catch(Exception $e)
                {
                    $this->db->rollback();       // roll back the transaction if we fail

                    $this->errors[$table]['success'] = 0;
                    $this->errors[$table]['msg']     = lang('vm_insert_fail');
                    $this->errors[$table]['transaction_error'] = $e->getMessage();
                    
                    $this->clear();    // reset validator data  // reset validator data

                    return FALSE;
                }
                
            }
        }
        
        if( ! i_ajax())  // If request not AJAX, add success key for native posts.
        {
            $this->errors[$table]['success'] = 0;
        }
        
        return FALSE;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Build where statements
    * 
    * @return void
    */
    public function _compile_select()
    {
        if(count($this->where) > 0)
        {
            foreach($this->where as $key => $val)
            {
                $this->db->where($key, $val);
            }
        }
        
        if(count($this->or_where) > 0)
        {
            foreach($this->or_where as $key => $val)
            {
                $this->db->or_where($key, $val);
            }
        }
        
        if(count($this->where_in) > 0)
        {
            foreach($this->where_in as $key => $val)
            {
                $this->db->where_in($key, $val);
            }
        }
        
        if(count($this->or_where_in) > 0)
        {
            foreach($this->or_where_in as $key => $val)
            {
                $this->db->or_where_in($key, $val);
            }
        }
        
        if(count($this->where_not_in) > 0)
        {
            foreach($this->where_not_in as $key => $val)
            {
                $this->db->where_not_in($key, $val);
            }
        }
        
        if(count($this->or_where_not_in) > 0)
        {
            foreach($this->or_where_not_in as $key => $val)
            {
                $this->db->or_where_not_in($key, $val);
            }
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Override to $settings['field'] variable
     * 
     * @param string $field
     * @param string $type
     * @param string $val 
     */
    public function set_field($field, $type = 'rules', $val = '')
    {
        $this->settings['fields'][$field][$type] = $val;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Do type casting foreach value
    * 
    * @param mixed $field
    * @param mixed $default
    * @return string
    */
    public function _set_value($field, $default = '')
    {
        if( ! isset($this->settings['fields'][$field]['type']))
        {
            return $default;  // No type, return default value.
        }
        
        $type  = strtolower($this->settings['fields'][$field]['type']);

        ###########
        
        $value = lib('Validator')->set_value($field, $default);
        
        ###########
            
        if($type == 'string')
        {
            return form_prep($value);  
        }
        
        if($type == 'int' OR $type == 'integer')
        {
            return (int)$value;
        }
        
        if($type == 'float')
        {
            return (float)$value;
        }
    
        if($type == 'double')
        {
            return (double)$value;
        }
        
        if($type == 'bool' OR $type == 'boolean')
        {
            return (boolean)$value;
        }
        
        if($type == 'object')
        {
            return (object)$value;
        }
        
        if($type == 'mixed')
        {
            return $value;
        }
        
        if($type == 'null')
        {
            return 'NULL';
        }
        
        return $value;   // Unknown type.
    }
    
    // --------------------------------------------------------------------

        
    /**
    * Delete a record from current table 
    * using ID
    * 
    * @return boolean
    */
    public function delete()
    {
        lib('ob/Lang')->load('ob/vm');  // Load the language file
        
        $v_data = array();

        $db     = $this->item('database');
        $table  = $this->item('table');
        $id     = ($this->item('primary_key') !== FALSE) ? $this->item('primary_key') : 'id';
        
        $has_rules = FALSE;
        foreach($this->settings['fields'] as $k => $v)
        {
            if(isset($this->settings['fields'][$k]['rules']))  // validation used or not
            {
                $has_rules = TRUE;
            }
            
            if(isset($this->property[$k])) // set validations.
            {
                $v_data[$k] = $this->property[$k];
            }
        }
        
        // If isset a delete ID.
        if( ! isset($this->{$id}) AND count($this->where) == 0 AND count($this->where_in) == 0)
        {
            $this->where[$id] = $this->{$id};
        }
        
        if(count($this->where) == 0 AND count($this->where_in) == 0)
        {
            throw new Exception('Please set an ID or use $model->where() function before the delete operation.');
        }
        
        if($has_rules)  // if we have validation rules ..
        {
            $validator = $this->validate($v_data);  // do validation
        }
        else
        {
            $validator = TRUE;  // don't do validation
        }

        if($validator)
        {
            try {

                $this->db->transaction(); // begin the transaction

                $this->_before_delete();
                
                $this->_compile_select();
                $this->errors[$table]['affected_rows'] = $this->db->delete($table);

                $this->_after_delete();
                
                $this->db->commit();    // commit the transaction
                
                $this->errors[$table]['success'] = 1;
                $this->errors[$table]['msg']     = lang('vm_delete_success');
                
                $this->clear();    // reset validator data

                return TRUE;

            } catch(Exception $e)
            {
                $this->db->rollback();       // roll back the transaction if we fail

                $this->errors[$table]['success'] = 0;
                $this->errors[$table]['msg']     = lang('vm_delete_fail');
                $this->errors[$table]['transaction_error'] = $e->getMessage();

                $this->clear();    // reset validator data 

                return FALSE;
            }

        }
        
        if( ! i_ajax())  // If request not AJAX, add success key for native posts.
        {
            $this->errors[$table]['success'] = 0;
        }
            
        return FALSE;
    }

    // --------------------------------------------------------------------
    
    /**
     * Return latest sql query
     *
     * @return type 
     */
    public function debug()
    {
        if(ENV !== 'LIVE')
        {
            return $this->db->last_query();
        }
        
        return;
    }
    
    // --------------------------------------------------------------------

    /**
    * Covert string to md5 hash
    *
    * @param  string
    */
    public function _md5($str)
    {
        return md5($str);
    }
    
    // ---------------------------------------------------------------------
    
    /**
    * Clear All Variables.
    * 
    */
    public function clear()
    {
        if(is_object($this->db))
        {
            $this->db->_reset_select();  // Reset CRUD variables.
        }
        
        lib('ob/Validator')->clear(); // Clear validation settings.
        
        $this->where           = array();   // Reset Select
        $this->or_where        = array();
        $this->where_in        = array();
        $this->or_where_in     = array();
        $this->where_not_in    = array();
        $this->or_where_not_in = array();
        
        $this->function   = array();
        $this->property   = array();
        $this->no_save    = array(); 
        $this->validation = FALSE;

        
        // DON'T reset below the variables
        /*
            $this->errors     = array();  // Validation errors.
            $this->values     = array();  // Filtered safe values.
        */
    }
    
    // ---------------------------------------------------------------------
    
    /**
    * Before save
    * 
    * @return void
    */
    public function _before_save()
    {
        if(method_exists($this, 'before_save'))
        {
            $this->before_save();
        }
    }
    
    // ---------------------------------------------------------------------
    
    /**
    * After save
    * 
    * @return void
    */
    public function _after_save()
    {
        if(method_exists($this, 'after_save'))
        {
            $this->after_save();
        }
    }
    
    // ---------------------------------------------------------------------
    
    /**
    * Before delete
    * 
    * @return void
    */
    public function _before_delete()
    {
        if(method_exists($this, 'before_delete'))
        {
            $this->before_delete();
        }
    }
    
    // ---------------------------------------------------------------------
    
    /**
    * After delete
    * 
    * @return void
    */
    public function _after_delete()
    {
        if(method_exists($this, 'after_delete'))
        {
            $this->after_delete();
        }
    }
    
}

// END Validation Model Class

/* End of file Vmodel.php */
/* Location: ./obullo/core/Vmodel.php */