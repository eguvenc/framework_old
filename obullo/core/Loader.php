<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009 - 2012.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         Obullo
 * @author          Obullo.com
 * @subpackage      Obullo.core
 * @copyright       Obullo Team
 * @license
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Loader Class (Obullo Loader)
 * Load Obullo library, model, config, lang and any other files ...
 */

Class loader {

    /**
    * Track "local" helper files.
    * @var array
    */
    public static $_helpers      = array();

    /**
    * Track "base" helper files.
    * @var array
    */
    public static $_base_helpers = array();
    
    /**
    * Track "overriden" helpers.
    *
    * @var array
    */
    public static $_overriden_helpers = array();

    /**
    * Track db names.
    * @var array
    */
    public static $_databases    = array();
    
    /**
    * Track model names.
    * @var array
    */
    public static $_models       = array();

    /**
    * loader::lib();
    *
    * load libraries from /module folder. (current module) 
    *
    * @param    mixed $class
    * @param    mixed $params_or_no_instance array | null | false 
    * @param    string | boolean $object_name_or_new_instance
    * @return   self::_library()
    */
    public static function lib($class = '', $params_or_no_instance = '', $object_name_or_new_instance = '')
    {
        if($class == '')
        {
            return FALSE;
        }
        
        if(is_bool($object_name_or_new_instance))
        {
            $object_name  = '';
            $new_instance = $object_name_or_new_instance;
        }
        else 
        {
            $new_instance = FALSE;
            $object_name  = $object_name_or_new_instance;
        }
        
        // Obullo Libraries
        // --------------------------------------------------------------------
        
        if(strpos($class, 'ob/') === 0)
        {
            $library = strtolower(substr($class, 3));
            
            if($params_or_no_instance === FALSE)
            {   
                return lib($class, $params_or_no_instance, $new_instance);
            } 
            
            // If someone use HMVC we need to create new instance() foreach Library
            if(i_hmvc() == FALSE)
            {
                if (isset(this()->{$library}) AND is_object(this()->{$library}))
                {
                    return;
                }
            }

            this()->{$library} = lib($class, $params_or_no_instance, $new_instance);
            
            return;
        }
        
        self::_library($class, $params_or_no_instance, $object_name, $new_instance);
    }

    // --------------------------------------------------------------------
                             
    /**
    * Obullo Library Loader.
    *
    * @param    string $class class name
    * @param    array | boolean $params_or_no_ins __construct() params  | or | No Instantiate
    * @param    boolean $new_instance create new instance
    *
    * @return   void
    */
    protected static function _library($class, $params_or_no_ins = '', $object_name = '', $new_instance = FALSE)
    {
        $data = self::load_file($class, 'libraries', ($params_or_no_ins == FALSE) ? TRUE : FALSE);

        $class_var = '';

        #####################

        require_once($data['path'].$data['filename'].EXT);

        #####################

        $class_var = strtolower($data['filename']);

        if($object_name != '') 
        {
            $class_var = $object_name;
        }

        if(is_array($params_or_no_ins))  // HMVC need to create new instance() foreach Library
        {
            if(i_hmvc() == FALSE AND $new_instance == FALSE)
            {
                if (isset(this()->$class_var) AND is_object(this()->$class_var))
                {
                    return;
                }
            }

            if(class_exists($data['filename']))
            {
                this()->$class_var = new $data['filename']($params_or_no_ins);
            }

            return;
        }
        elseif($params_or_no_ins === FALSE)
        {
            return;
        }
        else
        {
            if (isset(this()->$class_var) AND is_object(this()->$class_var))
            {
                return;
            }

            if(class_exists($data['filename']))
            {
                $Class = ucfirst($data['filename']);

                this()->$class_var = new $Class();
            }

            return;
        }
    }

    // --------------------------------------------------------------------

    /**
    * loader::model();
    * loader::model('subfolder/model_name')  sub folder load
    * loader::model('../module/model_name')  model load from outside directory
    * loader::model('modelname', FALSE); no instantite just include class.
    * 
    * @param     string $model
    * @param     string $object_name_OR_NO_INS
    * @param     array | boolean $params (construct params) | or | Not Instantiate just include file
    * @return    void
    */
    public static function model($model, $object_name_or_no_ins = '', $params_or_no_ins = '')
    {
        $new_instance = FALSE;
        
        if($object_name_or_no_ins === TRUE)
        {
            $new_instance = TRUE;
            $object_name_or_no_ins = '';
        }
         
        $case_sensitive = ($object_name_or_no_ins === FALSE || $params_or_no_ins === FALSE) ? $case_sensitive = TRUE : FALSE;
        
        $data = self::load_file($model, 'models', FALSE , $case_sensitive);

        self::_model($data['path'], $data['filename'], $object_name_or_no_ins, $params_or_no_ins, $new_instance);
    }

    // --------------------------------------------------------------------

    /**
    * Load _model
    *
    * @access    private
    * @param     string $file
    * @param     string $model_name
    * @param     string $object_name
    * @param     array | boolean  $params_or_no_ins
    * @param     boolean $new_instance  create new instance
    * @version   0.1
    * @return    void
    */
    protected static function _model($path, $model_name, $object_name = '', $params_or_no_ins = '', $new_instance = FALSE)
    {
        $model_var = $model_name;
        
        if($object_name != '' OR $object_name != NULL)
        {
            $model_var = $object_name;
        }
        
        if(i_hmvc() == FALSE AND $new_instance == FALSE) // If someone use HMVC we need to create new instance() foreach HMVC requests.
        {
            if (isset(this()->$model_var) AND is_object(this()->$model_var))
            {
                return;   
            }
        }
        
        #####################

        require_once($path . $model_name . EXT);
        
        #####################
        
        $model = ucfirst($model_name);

        if($params_or_no_ins === FALSE || $object_name === FALSE)
        {
            return;
        }

        if( ! class_exists($model, false)) // autoload false.
        {
            throw new Exception('You have a small problem, model name isn\'t right in here: '.$model);
        }

        loader::$_models[$model_var] = $model_var; // should be above instantiate od the model();

        this()->$model_var = new $model($params_or_no_ins);    // register($class); we don't need it

        // assign all loaded db objects inside to current model, support for Model_x { function __construct() { loader::database() }}
        this()->$model_var->_assign_db_objects();
    }

    // --------------------------------------------------------------------

    /**
    * loader::database();
    *
    * Database load.
    * This function loads the database for controllers
    * and model files.
    *
    * @param    mixed $db_name for manual connection
    * @param    boolean $return_object return to db object switch
    * @param    boolean $use_active_record @deprecated
    * @return   void
    */
    public static function database($db_name_or_params = 'db', $return_object = FALSE)
    {
        $db_var = 'db';
        if(is_array($db_name_or_params) AND isset($db_name_or_params['variable']))
        {
            $db_var = $db_name_or_params['variable'];
        }
        elseif(is_string($db_name_or_params))
        {            
            $db_var = ($db_var != 'db') ? $db_name_or_params : 'db';
        }

        if (isset(this()->{$db_var}) AND is_object(this()->{$db_var}))  // Lazy Loading ..
        {
            if($return_object) // return to db object like libraries.
            {
                return this()->{$db_var};
            }

            return;
        }
        
        if( ! class_exists('OB_Database'))
        {
            if(is_bool($db_name_or_params) AND $db_name_or_params === FALSE)  // No instantite
            {
                $database = lib('ob/Database', FALSE); // Just Include Database File No Instantiate.

                return;
            }
        }
        
        ################
        
        $database = lib('ob/Database', '', TRUE); // Database Object Must Be New.

        ################
        
        if($return_object)
        {
            return $database->connect($db_var, $db_name_or_params);
        }

        this()->{$db_var} = '';
        this()->{$db_var} = $database->connect($db_var, $db_name_or_params);  // Connect to Database
        
        loader::$_databases[$db_var] = $db_var;

        self::_assign_db_objects($db_var);   
    }

    // --------------------------------------------------------------------

    /**
    * loader::helper();
    *
    * loader::helper('subfolder/helper_name')  local sub folder load
    * loader::helper('../outside_folder/helper_name')  outside directory load
    *
    * We have three helper directories
    *   o Obullo/helpers: ob/ helpers
    *   o App/helpers   : app/ helpers
    *   o Local/helpers : module helpers
    *
    * @param    string $helper
    * @return   void
    */
    public static function helper($helper)
    {
        // Obullo Helpers
        // --------------------------------------------------------------------
        
        if(strpos($helper, 'ob/') === 0)
        {
            return loader::_helper(substr($helper, 3));
        }
                 
        // Core helpers
        // --------------------------------------------------------------------
        
        if(strpos($helper, 'core/') === 0) // Obullo Core Helpers
        {
            return loader::_helper(substr($helper, 5), true);
        }
       
        // Module Helpers
        // --------------------------------------------------------------------
        
        if( isset(self::$_helpers[$helper]) )
        {
            return;
        }
        
        $data = self::load_file($helper, $folder = 'helpers');

        include($data['path'].$data['filename'].EXT);

        self::$_helpers[$helper] = $helper;
    }

    // --------------------------------------------------------------------

    /**
    * Private helper loader.
    *
    * @param    string $helper
    * @return   void
    */
    protected static function _helper($helper, $core = FALSE)
    {            
        if( isset(self::$_base_helpers[$helper]) )
        {
            return;
        }

        $core_path = ($core) ? 'core'. DS : '';
        $prefix      = config('subhelper_prefix');
     
        //------ end extensions override support -----//

        if( ! isset(self::$_overriden_helpers[$helper]))
        {
            if(file_exists(APP .'helpers'. DS .$prefix. $helper. EXT))  // If application my_helper exist.
            {
                include(APP .'helpers'. DS .$prefix. $helper. EXT);

                self::$_base_helpers[$prefix . $helper] = $prefix . $helper;

                self::$_overriden_helpers[$helper] = $helper;
            }
        }

        include(BASE .'helpers'. DS .$core_path . $helper. EXT);

        self::$_base_helpers[$helper] = $helper;        
    }

    // --------------------------------------------------------------------

    /**
    * Load language files.
    * 
    * @param string $file
    * @param string $folder
    * @param bool $return 
    */
    public static function lang($file, $folder = '', $return = FALSE)
    {
        lib('ob/Lang')->load($file, $folder, NULL, $return);
    }

    // --------------------------------------------------------------------
    
    /**
    * Load config files.
    * 
    * @param string $file
    * @param bool $use_sections
    * @param bool $fail_gracefully 
    */
    public static function config($file, $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        lib('ob/Config')->load($file, $use_sections, $fail_gracefully);
    }
 
    // --------------------------------------------------------------------
    
    /**
    * Common file loader for models and
    * helpers functions.
    *
    * @param string $file_url
    * @param string $folder
    * @param string $loader_func
    *
    * return array  file_name | file
    */
    public static function load_file($file_url, $folder = 'helpers', $case_sensitive = FALSE, $extra_path = '')
    {
        $realname   = ($case_sensitive) ? trim($file_url, '/') : strtolower(trim($file_url, '/'));
        $root       = rtrim(MODULES, DS); 
        $sub_root   = lib('ob/Router')->fetch_directory(). DS .$folder. DS;
       
        if($extra_path != '')
        {
            $extra_path = str_replace('/', DS, trim($extra_path, '/')) . DS;
        } 
        
        if(strpos($file_url, 'app/') === 0)  // APP folder
        {
            $realname = strtolower(substr($file_url, 4));
            $root     = APP . $folder;
            $sub_root = '';
        }

        if(strpos($realname, '../') === 0)   // ../module folder request
        {
            $paths      = explode('/', substr($realname, 3));
            $filename   = array_pop($paths);         // get file name
            $modulename = array_shift($paths);       // get module name
            
            $sub_path   = '';
            if( count($paths) > 0)
            {
                $sub_path = implode(DS, $paths) . DS;      // .public/css/sub/welcome.css  sub dir support
            }
            
            $modulename     = ($modulename == '') ? '' : $modulename . DS;
            
            $return = array();
            $return['filename'] = $filename;
            $return['path']     = MODULES .$modulename . $folder . DS . $sub_path.$extra_path;
       
            return $return;
        }

        if(strpos($realname, '/') > 0)         //  Sub folder request
        {
            $paths      = explode('/',$realname);   // paths[0] = path , [1] file name
            $filename   = array_pop($paths);         // get file name
            
            $sub_path   = '';
            if( count($paths) > 0)
            {
                $sub_path = implode(DS, $paths) . DS;      // .public/css/sub/welcome.css  sub dir support
            }
            
            $return['filename'] = $filename;
            $return['path']     = $root. DS .$sub_root.$sub_path.$extra_path;
            
            return $return;
        }
        
        if($folder == 'lang')
        {
            return array('filename' => $realname, 'path' => $root. DS .$sub_root.$extra_path);
        }
        
        return array('filename' => $realname, 'path' => $root. DS .$sub_root);
    }
   
    // --------------------------------------------------------------------

    /**
    * Assign db objects to all Models
    *
    * @param   string $db_var
    * @return  void
    */
    protected static function _assign_db_objects($db_var = '')
    {
        if (count(loader::$_models) == 0 || ! is_object(this()))
        {
            return;
        }
        
        foreach (loader::$_models as $model_name)
        {
            if( ! isset(this()->$model_name) || is_object(this()->$model_name->$db_var))  // lazy loading
            {
                return;
            }

            if(is_object(this()->$db_var))
            {
                this()->$model_name->$db_var = this()->$db_var;
            }
        }
    }
}

// END Loader Class

/* End of file Loader.php */
/* Location: ./obullo/core/Loader.php */