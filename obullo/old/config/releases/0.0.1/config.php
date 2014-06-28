<?php

/**
 * Config Class
 * 
 * This class contains functions that enable config files to be managed
 *
 * @package     packages
 * @subpackage  config
 * @category    configuration
 * @link        
 */

Class Config {    
    
    public $config    = array();
    public $is_loaded = array();

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
        global $config, $logger;

        $this->config = $config;
        $logger->debug('Config Class Initialized');
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
        global $config, $logger;

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
            throw new Exception('Your '. $file .' file does not appear to contain a valid configuration array. Please create $config variables in your ' .$file);
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

        $logger->debug('Config file loaded: '.$file);
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
    public function getItem($item, $index = '')
    {    
        global $logger;

        if ($index == '')
        {    
            if ( ! isset($this->config[$item]))
            {
                $logger->info('Requested config item "'.$item.'" not found, be sure providing right name');

                return false;
            }
            
            $pref = $this->config[$item];
        }
        else
        {
            if ( ! isset($this->config[$index]))
            {
                $logger->info('Requested config index "'.$item.'" not found, be sure providing right name');

                return false;
            }

            if ( ! isset($this->config[$index][$item]))
            {
                $logger->info('Requested config item "'.$item.'" not found, be sure providing right name');
                
                return false;
            }

            $pref = $this->config[$index][$item];
        }

        return $pref;
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
    public function getSlashItem($item)
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

}

// END Config Class

/* End of file Config.php */
/* Location: ./packages/config/releases/0.0.1/config.php */