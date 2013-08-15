<?php

/**
 * Locale Class ( Language )
 *
 * @package       Obullo
 * @subpackage    locale
 * @category      language
 * @author        Obullo Team
 * @link
 */
Class Locale {
    
    public $language  = array();
    public $is_loaded = array();
    
    public static $instance;

    function __construct()
    {
        // Set Default language from $_COOKIE['__langlocale']
        // $this->set_language();
        
        log\me('debug', "Locale Class Initialized");    
    }

    // --------------------------------------------------------------------

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
    * Load a language file
    *
    * @access   public
    * @param    string   $filename the name of the language file to be loaded. Can be an array
    * @param    string   $idiom the language folder (english, etc.)
    * @param    bool     $return return to $lang variable if you don't merge
    * @return   mixed
    */
    public function load($filename = '', $idiom = '', $return = false)
    {
        if ($idiom == '')
        {
            $default = config('language');
            $idiom   = ($default == '') ? 'english' : $default;
        }
        
        if (in_array($filename, $this->is_loaded, true))
        {
            return;
        }
        
        if( ! is_dir(APP .'locale'. DS .$idiom))
        {
            throw new Exception('The locale folder '.APP .'locale'. DS .$idiom.' seems not a folder.');
        }
        
        require(APP .'locale'. DS .$idiom. DS .$filename. EXT);

        if ( ! isset($lang))
        {
            log\me('error', 'Locale file does not contain $lang variable: '. APP .'locale'. DS .$idiom. DS .$filename. EXT);
            
            return;
        }

        if ($return)
        {
            return $lang;
        }

        $this->is_loaded[] = $filename;
        $this->language    = array_merge($this->language, $lang);

        unset($lang);

        log\me('debug', 'Locale file loaded: '. APP .'locale'. DS .$idiom. DS .$filename. EXT);
        
        return true;
    }
}

/* End of file locale.php */
/* Location: ./ob/locale/releases/0.0.1/locale.php */