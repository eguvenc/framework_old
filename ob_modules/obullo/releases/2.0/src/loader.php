<?php

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
    * Track db names.
    * @var array
    */
    public static $_databases    = array();
    
    /**
    * Track model names.
    * @var array
    */
    public static $_models       = array();

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
            if (isset(getInstance()->$model_var) AND is_object(getInstance()->$model_var))
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
            show_error('You have a small problem, the model name isn\'t right in here: <b>'.$model.'<b/>');
        }

        loader::$_models[$model_var] = $model_var; // should be above instantiate od the model();

        getInstance()->$model_var = new $model($params_or_no_ins);    // register($class); we don't need it

        // assign all loaded db objects inside to current model, support for Model_x { function __construct() { loader::database() }}
        getInstance()->$model_var->_assign_db_objects();
    }

    // --------------------------------------------------------------------

    public static function library($library)
    {
        if(strpos($library, 'ob/') === 0)
        {
            $libraryname = strtolower(substr($library, 3));
            $packages    = get_config('packages');
            
            if( ! isset($packages['dependencies'][$libraryname]['component']) )
            {
                show_error('Please install the <b>'.$libraryname.'</b> package.');
            }
            
            if($packages['dependencies'][$libraryname]['component'] == 'library')
            {
                $Class = ucfirst($libraryname);
                getInstance()->{$libraryname} = new $Class(); // Atuoload the library from ob_modules using php5 autoloader.
            }
            
            return;
        }
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
        
        if (isset(getInstance()->{$db_var}) AND is_object(getInstance()->{$db_var}))  // Lazy Loading ..
        {
            if($return_object) // return to db object like libraries.
            {
                return getInstance()->{$db_var};
            }

            return;
        }
        
        $packages = get_config('packages');
        $db_class = $packages['db_class'];
        
        if( ! class_exists($db_class))
        {
            require (OB_MODULES .strtolower($db_class). DS .'releases'. DS .$packages['dependencies'][strtolower($db_class)]['version']. DS .strtolower($db_class). EXT);
        }
        
        if(is_bool($db_name_or_params) AND $db_name_or_params === FALSE)  // No instantite
        {
            return;
        }

        ################

        $database = new $db_class(); // Database Object Must Be New.

        ################

        if($return_object)
        {
            return $database->connect($db_var, $db_name_or_params);
        }

        getInstance()->{$db_var} = '';
        getInstance()->{$db_var} = $database->connect($db_var, $db_name_or_params);  // Connect to Database
        
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
    *   o Local/helpers : module helpers
    *
    * @param    string $helper
    * @return   void
    */
    public static function helper($helper)
    {
        // Obullo Helpers
        // --------------------------------------------------------------------
         
        if( isset(self::$_helpers[$helper]) )
        {
            return;
        }
        
        if(strpos($helper, 'ob/') === 0)
        {
            $helpername = substr($helper, 3);
            $packages   = get_config('packages');
            
            if( ! isset($packages['dependencies'][$helpername]['component']) )
            {
                show_error('Please install the <b>'.$helper.'</b> package.');
            }
            
            if($packages['dependencies'][$helpername]['component'] == 'helper')
            {
                require (OB_MODULES .$helpername. DS .'releases'. DS .$packages['dependencies'][$helpername]['version']. DS .$helpername. EXT);
            }

            self::$_helpers[$helper] = $helper;
            
            return;
        }
       
        // Module Helpers
        // --------------------------------------------------------------------
        
        $data = self::load_file($helper, $folder = 'helpers');

        include($data['path'].$data['filename'].EXT);

        self::$_helpers[$helper] = $helper;
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
        
        if($extra_path != '')
        {
            $extra_path = str_replace('/', DS, trim($extra_path, '/')) . DS;
        } 
       
        $sub_root   = Router::getInstance()->fetch_directory(). DS .$folder. DS;

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
        
        if($folder != 'locale')
        {
            $extra_path = '';
        }
        
        return array('filename' => $realname, 'path' => $root. DS .$sub_root.$extra_path);
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
        if (count(loader::$_models) == 0 || ! is_object(getInstance()))
        {
            return;
        }
        
        foreach (loader::$_models as $model_name)
        {
            if( ! isset(getInstance()->$model_name) || is_object(getInstance()->$model_name->$db_var))  // lazy loading
            {
                return;
            }

            if(is_object(getInstance()->$db_var))
            {
                getInstance()->$model_name->$db_var = getInstance()->$db_var;
            }
        }
    }
}

// END Loader Class

/* End of file loader.php */
/* Location: ./ob_modules/obullo/releases/2.0/src/loader.php */