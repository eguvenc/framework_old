<?php
namespace Ob;

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

        log\me('debug', "Model Class Initialized");
    }
    
    /**
    * Assign all db objects to all Models.
    * 
    * Very bad idea assign all library objects to model !!!
    * We assign just db objects.
    */
    protected function _assign_db_objects()
    {
           
        /*
        foreach(loader::$_databases as $db_var)
        {
            if(method_exists($this, '__get') OR method_exists($this, '__set'))
            {
                if(isset(getInstance()->$db_var) AND is_object(getInstance()->$db_var))
                {
                    $this->$db_var = getInstance()->$db_var;  // to prevent some reference errors
                }
            }
            else
            {
                if(isset(getInstance()->$db_var) AND is_object(getInstance()->$db_var))
                {
                    $this->$db_var = &getInstance()->$db_var;  // to prevent some reference errors
                }
            }
        }
        */
    }
      
}

// END Model Class

/* End of file model.php */
/* Location: ./ob/model/releases/0.0.1/model.php */
