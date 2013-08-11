<?php
namespace Ob\View;

/**
 * View Class
 *
 * Display html files.
 *
 * @package       Obullo
 * @subpackage    View
 * @category      Templates
 * @author        Obullo Team
 */

Class View {

    public $var          = array(); // String type view variables
    public $array        = array(); // Array type view variables
    public $data         = array(); // Mixed type view variables
    public $path         = NULL;
    
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
        $this->path = MODULES .\Ob\getInstance()->router->fetch_directory(). DS .'views'. DS;
        
        \Ob\log\me('debug', "View Class Initialized");
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
    public function set_path($path = '')
    {
        $this->path = ($path == '') ? MODULES .'views'. DS : MODULES .str_replace('/', DS, trim($path, '/')). DS .'views'. DS;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get view path
     * 
     * @return string
     */
    public function get_path()
    {
        return (string)$this->path;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Load view files.
    * 
    * @param string $filename view name
    * @param mixed  $data view data
    * @param booelan $string fetch the file as string or include file
    * @return void | string
    */
    public function load($filename, $data = '', $string = FALSE)
    {
        if(function_exists('\Ob\getInstance') AND  is_object(\Ob\getInstance()))
        {
            foreach(array_keys(get_object_vars(\Ob\getInstance())) as $key) // This allows to using "$this" variable in all views files.
            {
                // Don't do lazy loading => isset() in here object variables always
                // must be ## NEW ##. 
                // e.g. $this->config->item('myitem')
                
                $this->{$key} = \Ob\getInstance()->{$key};          
            }
        }
        
        //-----------------------------------
                
        $data = $this->_set_data($data); // Enables you to set data that is persistent in all views.

        //-----------------------------------

        if(is_array($data) AND count($data) > 0) 
        { 
            extract($data, EXTR_SKIP); 
        }

        ob_start();

        // If the PHP installation does not support short tags we'll
        // Please open it your php.ini file. ( short_tag = on ).

        include($this->path . $filename . EXT);
        
        \Ob\log\me('debug', 'View file loaded: '.\Ob\error\secure_path($this->path). $filename . EXT);

        if($string === TRUE)
        {
            $output = ob_get_contents();
            @ob_end_clean();

            return $output;
        }
        
        // Render possible Exceptional errors.
        $output = ob_get_contents();
        
        // Set Layout views inside to Output Class for caching functionality.
        \Ob\Output\Output::getInstance()->append_output($output);

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
    public function _set_data($data = '')
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
/* Location: ./ob/view/releases/0.0.1/view.php */