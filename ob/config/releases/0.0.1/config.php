<?php
namespace Config;

/**
 * Config Class
 * 
 * This class contains functions that enable config files to be managed
 *
 * @package     Obullo
 * @subpackage  config
 * @category    Modules
 * @author      Obullo Team
 * @link        
 */
Class Config
{    
    public $config          = array();
    public $is_loaded       = array();
    
    public static $instance;

    /**
    * Constructor
    *
    * Sets the $config data from the primary config.php file as a class variable
    *
    * @access  public
    * @return  void
    */
    public function __construct()
    {
        // Warning : Do not use lib($class);
        // 
        // Don't load any library in ***** __construct ******* function because of Obullo use 
        // the Config class __construct() method at Bootstrap loading level. When you try loading any library
        // in here you will get a Fatal Error.
        
        $this->config = getConfig();
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
    
    public static function setInstance($object)
    {
        if(is_object($object))
        {
            self::$instance = $object;
        }
        
        return self::$instance;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Load Config File
    *
    * @access   public
    * @param    string    the config file name
    * @return   boolean   if the file was loaded correctly
    */    
    public function load($filename = '', $use_sections = false)
    {
        $file = APP .'config'. DS .$filename. EXT;

        if (in_array($file, $this->is_loaded, true))
        {
            return true;
        }
    
        ######################
        
        include($file);
                
        ######################

        if ( ! isset($config) OR ! is_array($config))
        {
            throw new \Exception('Your '. $file .' file does not appear to contain a valid configuration array. Please create $config variables in your ' .$file);
        }
        
        if ($use_sections === true)
        {
            if (isset($this->config[$file]))
            {
                $this->config[$file] = array_merge($this->config[$file], $config);
            }
            else
            {
                $this->config[$file] = $config;
            }
        }
        else
        {
            $this->config = array_merge($this->config, $config);
        }

        $this->is_loaded[] = $file;
        
        unset($config);

        \log\me('debug', 'Config file loaded: '.$file);
        
        return true;
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Fetch a config file item
    *
    *
    * @access   public
    * @param    string    the config item name
    * @param    string    the index name
    * @param    bool
    * @return   string
    */
    public function item($item, $index = '')
    {    
        if ($index == '')
        {    
            if ( ! isset($this->config[$item]))
            {
                \log\me('info', 'Requested config item "'.$item.'" not found, be sure providing to right name.');
                
                return false;
            }

            $pref = $this->config[$item];
        }
        else
        {
            if ( ! isset($this->config[$index]))
            {
                \log\me('info', 'Requested config index "'.$item.'" not found, be sure providing to right name.');
                
                return false;
            }

            if ( ! isset($this->config[$index][$item]))
            {
                \log\me('info', 'Requested config item "'.$item.'" not found, be sure providing to right name.');
                
                return false;
            }

            $pref = $this->config[$index][$item];
        }

        return $pref;
    }
    
    // --------------------------------------------------------------------

    /**
    * Fetch a config file item - adds slash after item
    *
    * The second parameter allows a slash to be added to the end of
    * the item, in the case of a path.
    *
    * @access   public
    * @param    string    the config item name
    * @param    bool
    * @return   string
    */
    public function slashItem($item)
    {
        if ( ! isset($this->config[$item]))
        {
            return false;
        }

        $pref = $this->config[$item];

        if ($pref != '' AND substr($pref, -1) != '/')
        {    
            $pref .= '/';
        }

        return $pref;
    }
      
    // --------------------------------------------------------------------

    /**
    * Site URL
    *
    * @access   public
    * @param    string    the URI string
    * @param    boolean   switch off suffix by manually
    * @return   string
    */
    public function siteUrl($uri = '', $suffix = true)
    {
        if (is_array($uri))
        {
            $uri = implode('/', $uri);
        }
        
        if ($uri == '')
        {
            return $this->baseUrl() . $this->item('index_page');
        }
        else
        {
            $suffix = ($this->item('url_suffix') == false OR $suffix == false) ? '' : $this->item('url_suffix');
            
            return $this->baseUrl() . $this->slashItem('index_page'). trim($uri, '/') . $suffix;
        }
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Base URL
    * Returns base_url
    * 
    * @access public
    * @param string $uri
    * @return string
    */
    public function baseUrl($uri = '')
    {
        return $this->slashItem('base_url').ltrim($uri,'/');
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Public URL (Get the url for static media files)
    *
    * @access   public
    * @param    string uri
    * @param    bool $no_slash  no trailing slashes
    * @return   string
    */
    public function assetUrl($uri = '', $no_folder = false, $no_ext_uri_slash = false)
    {
        $extra_uri     = (trim($uri, '/') != '') ? trim($uri, '/').'/' : '';
        $asset_folder  = ($no_folder) ? '' : 'assets/';
        
        if($no_ext_uri_slash)
        { 
            $extra_uri = trim($extra_uri, '/');
        }
        
        return $this->slashItem('asset_url') .$asset_folder. $extra_uri;
    }
    
    // --------------------------------------------------------------------

    /**
    * Base Folder
    *
    * @access    public
    * @return    string
    */
    public function baseFolder()
    {
        $x = explode("/", preg_replace("|/*(.+?)/*$|", "\\1", trim(OB_MODULES, DS)));
        
        return $this->baseUrl() . end($x).'/';
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Set a config file item
    * alias of config_item we will deprecicate it later.
    *
    * @access   public
    * @param    string    the config item key
    * @param    string    the config item value
    * @return   void
    */
    public function setItem($item, $value)
    {
        $this->config[$item] = $value;
    }

}

// END Config Class

/* End of file Config.php */
/* Location: ./ob/config/releases/0.0.1/config.php */