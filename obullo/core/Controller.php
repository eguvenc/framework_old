<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009 - 2012.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         obullo    
 * @author          obullo.com
 * @copyright       Obullo Team.
 * @since           Version 1.0
 * @filesource
 * @license
 */
 
 /**
 * Controller Class.
 *
 * Main Controller class.
 *
 * @package         Obullo 
 * @subpackage      Obullo.core     
 * @category        Core
 * @version         0.2
 */

 /**
 * Main Controller Class ( Core of the Obullo ).
 * 
 * @package         Obullo 
 * @subpackage      Obullo.core     
 * @category        Core
 * @version         0.2
 * 
 */

Class Controller {

    private static $instance;

    public function __construct()       
    {   
        self::$instance = &$this;

        // Load Obullo Core Libraries
        // ------------------------------------
        
        $this->config = lib('ob/Config');
        $this->router = lib('ob/Router');
        $this->uri    = lib('ob/Uri');
        $this->output = lib('ob/Output');
        
        // Initialize to Module Libraries
        // ------------------------------------
        
        module_init();
        
        // ------------------------------------
    }

    // -------------------------------------------------------------------- 
    
    /**
    * Fetch or Set Controller Instance
    * 
    * @param type $new_instance
    * @return type 
    */
    public static function _instance($new_instance = '')
    {   
        if(is_object($new_instance))
        {
            self::$instance = $new_instance;
        }    

        return self::$instance;
    } 
    
}

// -------------------------------------------------------------------- 

/**
* Grab Obullo Super Object
* 
* A Pretty handy function this();
* We use "this()" function if not available $this anywhere.
*
* @param object $new_istance  
*/
function this($new_instance = '') 
{ 
    if(is_object($new_instance))  // fixed HMVC object type of integer bug in php 5.1.6
    {
        Controller::_instance($new_instance);
    }
    
    return Controller::_instance(); 
}

// END Controller Class

/* End of file Controller.php */
/* Location: ./obullo/core/Controller.php */