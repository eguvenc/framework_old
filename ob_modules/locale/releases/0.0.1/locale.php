<?php

/**
 * Obullo Language Class
 *
 * @package     Obullo
 * @subpackage  Locale
 * @category    Language
 * @author      Obullo Team
 * @link        
 */

// --------------------------------------------------------------------

/**
* Fetch a item of text from the language array
*
* @access   public
* @param    string  $item the language item
* @return   string
*/

if( ! function_exists('lang') ) 
{
    function lang($item = '')
    {
        $lang = Locale::getInstance();
        $item = ($item == '' OR ! isset($lang->language[$item])) ? FALSE : $lang->language[$item];
        
        return $item;
    }
}

// --------------------------------------------------------------------

Class Locale {
    
    public $language  = array();
    public $is_loaded = array();
    
    public static $instance;

    function __construct()
    {
        log_me('debug', "Locale Library Initialized");    
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
    * @param    string   $langfile the name of the language file to be loaded. Can be an array
    * @param    string   $idiom the language folder (english, etc.)
    * @param    bool     $return return to $lang variable if you don't merge
    * @return   mixed
    */
    public function load($langfile = '', $idiom = '', $return = FALSE)
    {
        if ($idiom == '')
        {
            $default = Config::getInstance()->item('language');
            $idiom   = ($default == '') ? 'english' : $default;
        }

        if(strpos($langfile, 'ob/') === 0)  // Obullo Core Lang
        {
            $langfile = strtolower(substr($langfile, 3));
            
            $data = array('filename' => $langfile, 'path' => BASE .'lang'. DS. trim($idiom, '/'));
            
            if(file_exists(APP .'lang'. DS. trim($idiom, '/') . DS. $langfile. EXT)) // check app path
            {
                $data = array('filename' => $langfile, 'path' => APP .'lang'. DS. trim($idiom, '/'));
            }
        } 
        else 
        {
            $data = loader::load_file($langfile, 'lang', FALSE, $idiom);
        }
        
        if (in_array($langfile, $this->is_loaded, TRUE))
        {
            return;
        }
        
        if( ! is_dir($data['path']))
        {
            throw new Exception('The locale folder '.$data['path'].' seems not a folder.');
        }

        $lang = get_static($data['filename'], 'lang', rtrim($data['path'], DS)); 
        

        if ( ! isset($lang))
        {
            log_me('error', 'Locale file contains no lang variable: ' . $data['path'] . DS . $data['filename']. EXT);
            
            return;
        }

        if ($return)
        {
            return $lang;
        }

        $this->is_loaded[] = $langfile;
        $this->language    = array_merge($this->language, $lang);

        unset($lang);

        log_me('debug', 'Locale file loaded: '.$data['path'] . DS . $data['filename']. EXT);
        
        return TRUE;
    }
}

/* End of file locale.php */
/* Location: ./ob_modules/locale/releases/0.0.1/locale.php */