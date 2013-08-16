<?php

/**
 * OBJECT DATA MODEL
 *
 * @package         Obullo 
 * @subpackage      odm
 * @category        Model
 * @version         0.0.1
 */                    

Class Odm extends Model {

    public $property   = array();  // User public variables, we set them in controller.
    public $errors     = array();  // Validation errors.
    public $values     = array();  // Filtered safe values.
    public $no_save    = array();  // No save fields, so save function will not save selected fields to database.
    public $validation = false;    // If form validation success we set it to true.
    
    public $function   = array();
    
    public $where           = array();
    public $or_where        = array();
    public $where_in        = array();
    public $or_where_in     = array();
    public $where_not_in    = array();
    public $or_where_not_in = array();
    
    public $schema = null;
    public $schema_fields = array();
    
    /**
    * Construct
    * 
    * @return void
    */
    public function __construct($schema = '')
    {                
        parent::__construct();
        
        if( ! is_object($schema))
        {
            throw new Exception('You must provide a $schema object.');
        }
        
        $this->schema = $schema;
        
        if( ! isset($this->schema->config['database'])) 
        {
            throw new Exception('Check your model it must be contains $schema->config[\'database\'] array.');
        }
        
        $db = $this->schema->config['database'];
                 
        if($db != '' AND $db != 'no')
        {
            $database = new Db(false);
            $this->db = $database->connect(); // Cannot assign by reference to overloaded object 
        }
                            
        if( ! isset($this->schema->config['table'])) // create random table name
        {
            $this->schema->config['table'] = 'unknown_'.rand();
        }
        
        ##### Reset validation data for multiple operations #######
        
        Validator::getInstance()->clear();
        
        ##### Reset validation data for multiple operations #######
        
        $this->schema_fields = get_object_vars($this->schema);
        
        unset($this->schema_fields['config']);
        
        log\me('debug', "Odm Class Initialized");
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
        $validator = Validator::getInstance();
        $validator->clear();

        if(count($_GLOBALS) == 0)
        {
            $_GLOBALS = $_REQUEST;
        }

        if(count($fields) == 0)
        {
            $fields = $this->schema_fields;
        }

        $validator->set('_globals', $_GLOBALS);
        $validator->set('_callback_object', $this);
    
        $table = $this->schema->config['table'];

        foreach($fields as $key => $val)
        {
            if(is_array($val))
            {
                if(isset($val['rules']) AND $val['rules'] != '')
                {
                    $validator->setRules($key, $val['label'], $val['rules']);
                }
            }
        }
        
        if($validator->run())   // Run validation
        {            
            foreach($fields as $key => $val)  // Set filtered values
            {
                $this->values[$table][$key] = $this->_setValue($key, $this->{$key});
            }
            
            $this->validation = true;
            
            return true;
        }
        else 
        {
            foreach($fields as $key => $val)  // Set validation errors..
            {
               if(isset($validator->_field_data[$key]['error']))
               {
                   $error = $validator->error($key, null, null);

                   if( ! empty($error))
                   {
                       $this->errors[$table][$key] = $error;
                   }
               }
              
               // Set filtered values
               $this->values[$table][$key] = $this->_setValue($key, $this->{$key});
            }
            
            $this->validation = false;
            
            return false;
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
        getInstance()->locale->load('obullo');  // Load the language file
        
        $v_data = array();   // validation fields data
        $db_fields = $this->schema_fields;
        
        if(count($fields) > 0)
        {
            unset($db_fields);
            
            foreach($fields as $f_key => $v)
            {
                $db_fields[$f_key] = $this->schema_fields[$f_key];
            }
        }

        foreach($db_fields as $k => $v)
        {
            if($this->{$k} != '')
            {
                $v_data[$k] = $this->_setValue($k, $this->{$k});
            }
            else
            {
                $v_data[$k] = $this->_setValue($k, i_get_post($k));
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
        $table = $this->schema->config['table'];
        
        if($key == 'transaction')
        {
            if(isset($this->errors[$table]['transaction_error']))
            {
                return $this->errors[$table]['transaction_error'];
            } 
            else 
            {
                return;
            }
        }
       
        if(isset($this->errors[$table]))
        {
            if(isset($this->errors[$table][$key]))
            {
                return $this->errors[$table][$key];
            }
            
            if($key != '')
            {
                return;
            }
            
            return $this->errors[$table];
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
    public function buildQueryErrors()
    {
        $table = $this->schema->config['table'];
        
        $http_query = array();
        if(isset($this->errors[$table]))
        {
            foreach($this->errors[$table] as $key => $val)
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
        $table = $this->schema->config['table'];
        
        if(isset($this->values[$table]))
        {
            if(isset($this->values[$table][$field]))
            {
                return $this->values[$table][$field];
            }
            
            return $this->values[$table];
        }
        
        return $this->values;
    }

    // --------------------------------------------------------------------

    /**
    * Set Custom Vmodel error for field.
    *
    * @param string $key or $field
    */
    public function setError($key, $error)
    {
        $table = $this->schema->config['table'];
        
        $this->errors[$table][$key] = $error;
       
        if(isset($this->schema_fields[$key])) // set a validation error.
        {
            Validator::getInstance()->_field_data[$key]['error'] = $error;
        }
    }

    // --------------------------------------------------------------------

    /**
    * Set Custom form json function.
    *
    * @param string $key name of function
    */
    public function setCustomKey($name, $val)
    {
        $table = $this->schema->config['table'];
        
        $this->function[$table]['name'] = $name;
        $this->function[$table]['val']  = $val;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Get the form json function name and values.
    *
    * @param string $type
    */
    public function customKey($type = 'name')
    {   
       $table = $this->schema->config['table'];
        
       if($type == '')
       {
           if(isset($this->function[$table]['name']))
           {
               return true;
           }
           
           return false;
       }
       
       if(isset($this->function[$table][$type]))
       {
           return $this->function[$table][$type];
       }
       
       return false;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Set value for field.
    *
    * @param string $key or $field
    */
    public function setValue($key, $value)
    {
        $this->values[$this->schema->config['table']][$key] = $value;
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
            return(null);
        }
    }
    
    // --------------------------------------------------------------------

    /**
    * Some times we don't want save some fields or that 
    * the fields which we haven't got in the db tables we have to validate them.
    * To overcome to this problem we use the $model->noSave(); function.
    * 
    * @param type $key
    */
    public function noSave($key)
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
    public function orWhere($key = '', $val = '')
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
    public function whereIn($key = '', $val = array())
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
    public function orWhereIn($key = '', $val = array())
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
    public function whereNotIn($key = '', $val = array())
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
    public function orWhereNotIn($key = '', $val = array())
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
    * @return  false | Integer
    */
    public function save()
    {
        getInstance()->locale->load('obullo');
                   
        $v_data = array();   // validation fields data
        $s_data = array();   // mysql insert / update fields data
        $table  = $this->schema->config['table'];
        $id     = ($this->schema->config['primary_key'] != '') ? $this->schema->config['primary_key'] : 'id';
        
        if(count($this->no_save) > 0)
        {
            foreach($this->no_save as $k)
            {
                unset($this->schema_fields[$k]);
            }
        }

        $has_rules = false;
        
        foreach($this->schema_fields as $k => $v)
        {
            if(strpos($k, '[]') > 0)  // remove multiple key names from save key ..
            {
                $k = str_replace('[]', '', $k);
            }
            
            if(isset($this->schema_fields[$k]['rules']))  // validation used or not
            {
                $has_rules = true;
            }
            
            if($this->{$k} != '')
            {
                $v_data[$k] = $this->_setValue($k, $this->property[$k]);
            }
            
            if($this->{$k} != '')
            {
                if(isset($this->schema_fields[$k]['func']))  // functions ..
                {
                    $function_string = trim($this->schema_fields[$k]['func'], '|');

                    if(strpos($function_string, '|') > 0)
                    {
                       $functions = explode('|', $this->schema_fields[$k]['func']);
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

                $v_data[$k] = $this->_setValue($k, i_get_post($k));
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
            $validator = true;  // don't do validation
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

                    $this->_beforeSave();
                                    
                    $this->_compileSelect();                
                    $this->errors[$table]['affected_rows'] = $this->db->update($table, $s_data);
                    
                    $this->_afterSave();
                    
                    $this->db->commit();    // commit the transaction
                    
                    $this->errors[$table]['success'] = 1;
                    $this->errors[$table]['msg']     = lang('Data updated succesfully.');
                    
                    $this->clear();    // reset validator data

                    return true;

                } catch(Exception $e)
                {
                    $this->db->rollback();       // roll back the transaction if we fail

                    $this->errors[$table]['success'] = 0;
                    $this->errors[$table]['msg']     = lang('Data not saved, please do some changes.');
                    $this->errors[$table]['transaction_error'] = $e->getMessage();
                    
                    $this->clear();    // reset validator data

                    return false;
                }
                
            }
            else   // ELSE do insert ..
            {
                try {

                    $this->db->transaction(); // begin the transaction
                    
                    $this->_beforeSave();
                     
                    $this->_compileSelect();    
                    $this->errors[$table]['affected_rows'] = $this->db->insert($table, $s_data);
                    $this->values[$table][$id] = $this->db->insertId();  // add last inserted id.

                    $this->_afterSave();
                    
                    $this->db->commit();    // commit the transaction
                    
                    $this->errors[$table]['success'] = 1;
                    $this->errors[$table]['msg']     = lang('Data inserted succesfully.');
                    
                    $this->clear();    // reset validator data  // reset validator data

                    return true;

                } catch(Exception $e)
                {
                    $this->db->rollback();       // roll back the transaction if we fail

                    $this->errors[$table]['success'] = 0;
                    $this->errors[$table]['msg']     = lang('Data insert error.');
                    $this->errors[$table]['transaction_error'] = $e->getMessage();
                    
                    $this->clear();    // reset validator data  // reset validator data

                    return false;
                }
                
            }
        }
        
        if( ! i\ajax())  // If request not AJAX, add success key for native posts.
        {
            $this->errors[$table]['success'] = 0;
        }
        
        return false;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Build where statements
    * 
    * @return void
    */
    public function _compileSelect()
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
                $this->db->orWhere($key, $val);
            }
        }
        
        if(count($this->where_in) > 0)
        {
            foreach($this->where_in as $key => $val)
            {
                $this->db->whereIn($key, $val);
            }
        }
        
        if(count($this->or_where_in) > 0)
        {
            foreach($this->or_where_in as $key => $val)
            {
                $this->db->orWhereIn($key, $val);
            }
        }
        
        if(count($this->where_not_in) > 0)
        {
            foreach($this->where_not_in as $key => $val)
            {
                $this->db->whereNotIn($key, $val);
            }
        }
        
        if(count($this->or_where_not_in) > 0)
        {
            foreach($this->or_where_not_in as $key => $val)
            {
                $this->db->orWhereNotIn($key, $val);
            }
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Do type casting foreach value
    * 
    * @param mixed $field
    * @param mixed $default
    * @return string
    */
    public function _setValue($field, $default = '')
    {
        if( ! isset($this->schema_fields[$field]['type']))
        {
            return $default;  // No type, return default value.
        }
        
        $type  = strtolower($this->schema_fields[$field]['type']);
        $value = Validator::getInstance()->setValue($field, $default);
            
        if($type == 'string')
        {
            return form\prep($value);  
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
            return 'null';
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
        getInstance()->locale->load('obullo');  // Load the language file
        
        $v_data = array();

        $db     = $this->schema->config['database'];
        $table  = $this->schema->config['table'];
        $id     = ($this->schema->config['primary_key'] != '') ? $this->schema->config['primary_key'] : 'id';
        
        $has_rules = false;
        foreach($this->schema_fields as $k => $v)
        {
            if(isset($this->schema_fields[$k]['rules']))  // validation used or not
            {
                $has_rules = true;
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
            throw new Exception('Please set an ID using $model->where() before the delete operation.');
        }
        
        if($has_rules)  // if we have validation rules ..
        {
            $validator = $this->validate($v_data);  // do validation
        }
        else
        {
            $validator = true;  // don't do validation
        }

        if($validator)
        {
            try {

                $this->db->transaction(); // begin the transaction

                $this->_beforeDelete();
                
                $this->_compileSelect();
                $this->errors[$table]['affected_rows'] = $this->db->delete($table);

                $this->_afterDelete();
                
                $this->db->commit();    // commit the transaction
                
                $this->errors[$table]['success'] = 1;
                $this->errors[$table]['msg']     = lang('Data deleted succesfully.');
                
                $this->clear();    // reset validator data

                return true;

            } catch(Exception $e)
            {
                $this->db->rollback();       // roll back the transaction if we fail

                $this->errors[$table]['success'] = 0;
                $this->errors[$table]['msg']     = lang('Delete error or record already deleted.');
                $this->errors[$table]['transaction_error'] = $e->getMessage();

                $this->clear();    // reset validator data 

                return false;
            }

        }
        
        if( ! i_ajax())  // If request not AJAX, add success key for native posts.
        {
            $this->errors[$table]['success'] = 0;
        }
            
        return false;
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
            $this->db->_resetSelect();  // Reset CRUD variables.
        }
        
        Validator::getInstance()->clear(); // Clear validation settings.
        
        $this->where           = array();   // Reset Select
        $this->or_where        = array();
        $this->where_in        = array();
        $this->or_where_in     = array();
        $this->where_not_in    = array();
        $this->or_where_not_in = array();
        
        $this->function   = array();
        $this->property   = array();
        $this->no_save    = array(); 
        $this->validation = false;

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
    public function _beforeSave()
    {
        if(method_exists($this, 'beforeSave'))
        {
            $this->beforeSave();
        }
    }
    
    // ---------------------------------------------------------------------
    
    /**
    * After save
    * 
    * @return void
    */
    public function _afterSave()
    {
        if(method_exists($this, 'afterSave'))
        {
            $this->afterSave();
        }
    }
    
    // ---------------------------------------------------------------------
    
    /**
    * Before delete
    * 
    * @return void
    */
    public function _beforeDelete()
    {
        if(method_exists($this, 'beforeDelete'))
        {
            $this->beforeDelete();
        }
    }
    
    // ---------------------------------------------------------------------
    
    /**
    * After delete
    * 
    * @return void
    */
    public function _afterDelete()
    {
        if(method_exists($this, 'afterDelete'))
        {
            $this->afterDelete();
        }
    }
    
}

// END Odm Class

/* End of file odm.php */
/* Location: ./ob/odm/releases/0.0.1/odm.php */