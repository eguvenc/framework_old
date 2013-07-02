<?php

/**
 * Model Class.
 *
 * Main model class.
 *
 * @package         Obullo 
 * @subpackage      Obullo.core     
 * @category        Core Model
 * @version         0.1
 */                    

Class Model {

    public function __construct()
    {
        $this->_assign_db_objects();

        log_me('debug', "Model Class Initialized");
    }
    
    /**
    * Assign all db objects to all Models.
    * 
    * Very bad idea assign all library objects to model !!!
    * We assign just db objects.
    */
    public function _assign_db_objects()
    {
        $OB = getInstance();

        foreach(loader::$_databases as $db_var)
        {
            if(method_exists($this, '__get') OR method_exists($this, '__set'))
            {
                if(isset($OB->$db_var) AND is_object($OB->$db_var))
                {
                    $this->$db_var = $OB->$db_var;  // to prevent some reference errors
                }
            }
            else
            {
                if(isset($OB->$db_var) AND is_object($OB->$db_var))
                {
                    $this->$db_var = &$OB->$db_var;  // to prevent some reference errors
                }
            }
        }
    
    }
      
}

// END Model Class

/* End of file model.php */
/* Location: ./ob_modules/obullo/releases/2.0/src/model.php */
