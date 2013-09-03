<?php

/**
 * View Class
 *
 * Display html files.
 *
 * @package       packages
 * @subpackage    view
 * @category      views
 * @link
 */

Class View {

    public $var          = array(); // String type view variables
    public $array        = array(); // Array type view variables
    public $object       = array(); // Object type view variables
    public $data         = array(); // Mixed type view variables
    public $path         = null;
    
    public static $instance;
    
    /**
    * Constructor
    *
    * Sets the View variables and runs the compilation routine
    *
    * @version   0.1
    * @access    public
    * @return    void
    */
    public function __construct()
    {
        $this->path = MODS .getInstance()->router->fetchDirectory(). DS .'view'. DS;
        
        log\me('debug', "View Class Initialized");
    }
    
    // ------------------------------------------------------------------------

    public static function getInstance()
    {
       if( ! self::$instance instanceof self)
       {
           self::$instance = new self();
       } 
       
       return self::$instance;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Set view path
     * 
     * @param string $path 
     */
    public function setPath($folder = 'view', $path = '')
    {
        $this->path = ($path == '') ? MODS .$folder. DS : MODS .str_replace('/', DS, trim($path, '/')). DS .$folder. DS;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get view path
     * 
     * @return string
     */
    public function getPath()
    {
        return (string)$this->path;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Load view files.
    * 
    * @param string $filename view name
    * @param mixed  $data view data
    * @param booelan $include fetch the file as string or include.
    * @return void | string
    */
    public function load($filename, $include = true, $data = '')
    {
        if(function_exists('getInstance') AND  is_object(getInstance()))
        {
            foreach(array_keys(get_object_vars(getInstance())) as $key) // This allows to using "$this" variable in all views files.
            {
                // Don't do lazy loading => isset() in here object variables always
                // must be ## NEW ##. 
                // e.g. $this->config->item('myitem')
                
                $this->{$key} = getInstance()->{$key};          
            }
        }
        
        //-----------------------------------
                
        $data = $this->_setData($data); // Enables you to set data that is persistent in all views.

        //-----------------------------------

        if(is_array($data) AND count($data) > 0) 
        { 
            extract($data, EXTR_SKIP); 
        }

        ob_start();

        // If the PHP installation does not support short tags we'll
        // Please open it your php.ini file. ( short_tag = on ).

        include($this->path . $filename . EXT);
        
        log\me('debug', 'View file loaded: '.error\securePath($this->path). $filename . EXT);

        if($include === false)
        {
            $output = ob_get_contents();
            @ob_end_clean();

            return $output;
        }
        
        // Render possible Exceptional errors.
        $output = ob_get_contents();
        
        // Set Layout views inside to Output Class for caching functionality.
        $outputClass = getComponent('output');
        $outputClass::getInstance()->appendOutput($output);

        @ob_end_clean();

        return;
    }
    
    // ------------------------------------------------------------------------
    
    /**
    * Enables you to set data that is persistent in all views
    *
    * @author CJ Lazell
    * @param array $data
    * @access public
    * @return void
    */
    public function _setData($data = '')
    {
        if($data == '')
        {
            return;
        }
        
        if(is_object($data)) // object to array.
        {
            return get_object_vars($data);
        }
        
        if(is_array($data) AND count($data) > 0 AND count($this->data) > 0)
        {
            $this->data = array_merge((array)$this->data, (array)$data);
        }
        else 
        {
            $this->data = $data;
        }
        
        return $this->data;
    }
    
}

// END View Class

/* End of file View.php */
/* Location: ./packages/view/releases/0.0.1/view.php */