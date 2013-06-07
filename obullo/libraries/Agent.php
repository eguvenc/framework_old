<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         obullo
 * @author          obullo.com
 * @copyright       Obullo Team
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * User Agent Class
 *
 * Identifies the platform, browser, robot, or mobile devise of the browsing agent
 *
 * @package       Obullo
 * @subpackage    Libraries
 * @category      Libraries
 * @author        Obullo Team
 * @link
 */
Class OB_Agent {

    public $agent        = NULL;

    public $is_browser   = FALSE;
    public $is_robot     = FALSE;
    public $is_mobile    = FALSE;

    public $languages    = array();
    public $charsets     = array();

    public $platforms    = array();
    public $browsers     = array();
    public $mobiles      = array();
    public $robots       = array();

    public $platform     = '';
    public $browser      = '';
    public $version      = '';
    public $mobile       = '';
    public $robot        = '';

    /**
     * Constructor
     *
     * Sets the User Agent and runs the compilation routine
     *
     * @version   0.1
     * @access    public
     * @return    void
     */
    public function __construct()
    {
        if (isset($_SERVER['HTTP_USER_AGENT']))
        {
            $this->agent = trim($_SERVER['HTTP_USER_AGENT']);
        }

        if ( ! is_null($this->agent))
        {
            if ($this->_load_agent_file())
            {
                $this->_compile_data();
            }
        }

        log_me('debug', "User Agent Class Initialized");
    }

    // --------------------------------------------------------------------

    /**
     * Compile the User Agent Data
     *
     * @access    private
     * @return    bool
     */
    public function _load_agent_file()
    {
        $user_agents = get_config('agents');  // obullo changes ..

        $return = FALSE;

        if (isset($user_agents['platforms']))
        {
            $this->platforms = &$user_agents['platforms'];
            unset($user_agents['platforms']);
            $return = TRUE;
        }

        if (isset($user_agents['browsers']))
        {
            $this->browsers  = &$user_agents['browsers'];
            unset($user_agents['browsers']);
            $return = TRUE;
        }

        if (isset($user_agents['mobiles']))
        {
            $this->mobiles   = &$user_agents['mobiles'];
            unset($user_agents['mobiles']);
            $return = TRUE;
        }

        if (isset($user_agents['robots']))
        {
            $this->robots    = &$user_agents['robots'];
            unset($robots);
            $return = TRUE;
        }

        return $return;
    }

    // --------------------------------------------------------------------

    /**
     * Compile the User Agent Data
     *
     * @access    private
     * @return    bool
     */
    public function _compile_data()
    {
        $this->_set_platform();

        foreach (array('_set_browser', '_set_robot', '_set_mobile') as $function)
        {
            if ($this->$function() === TRUE)
            {
                break;
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Set the Platform
     *
     * @access    private
     * @return    mixed
     */
    public function _set_platform()
    {
        if (is_array($this->platforms) AND count($this->platforms) > 0)
        {
            foreach ($this->platforms as $key => $val)
            {
                if (preg_match("|".preg_quote($key)."|i", $this->agent))
                {
                    $this->platform = $val;
                    return TRUE;
                }
            }
        }
        $this->platform = 'Unknown Platform';
    }

    // --------------------------------------------------------------------

    /**
     * Set the Browser
     *
     * @access    private
     * @return    bool
     */
    public function _set_browser()
    {
        if (is_array($this->browsers) AND count($this->browsers) > 0)
        {
            foreach ($this->browsers as $key => $val)
            {
                if (preg_match("|".preg_quote($key).".*?([0-9\.]+)|i", $this->agent, $match))
                {
                    $this->is_browser = TRUE;
                    $this->version = $match[1];
                    $this->browser = $val;
                    $this->_set_mobile();
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * Set the Robot
     *
     * @access    private
     * @return    bool
     */
    public function _set_robot()
    {
        if (is_array($this->robots) AND count($this->robots) > 0)
        {
            foreach ($this->robots as $key => $val)
            {
                if (preg_match("|".preg_quote($key)."|i", $this->agent))
                {
                    $this->is_robot = TRUE;
                    $this->robot = $val;
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * Set the Mobile Device
     *
     * @access    private
     * @return    bool
     */
    public function _set_mobile()
    {
        if (is_array($this->mobiles) AND count($this->mobiles) > 0)
        {
            foreach ($this->mobiles as $key => $val)
            {
                if (FALSE !== (strpos(mb_strtolower($this->agent, config('charset')), $key)))
                {
                    $this->is_mobile = TRUE;
                    $this->mobile = $val;
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * Set the accepted languages
     *
     * @access    private
     * @return    void
     */
    public function _set_languages()
    {
        if ((count($this->languages) == 0) AND isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) 
        AND $_SERVER['HTTP_ACCEPT_LANGUAGE'] != ''
        AND is_string($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
            $languages = preg_replace('/(;q=[0-9\.]+)/i', '', strtolower(trim($_SERVER['HTTP_ACCEPT_LANGUAGE'])));

            $this->languages = explode(',', $languages);
        }

        if (count($this->languages) == 0)
        {
            $this->languages = array('Undefined');
        }
    }

    // --------------------------------------------------------------------

    /**
     * Set the accepted character sets
     *
     * @access    private
     * @return    void
     */
    public function _set_charsets()
    {  
        if ((count($this->charsets) == 0) AND isset($_SERVER['HTTP_ACCEPT_CHARSET']) 
        AND $_SERVER['HTTP_ACCEPT_CHARSET'] != '' 
        AND is_string($_SERVER['HTTP_ACCEPT_CHARSET']))
        {
            $charsets = preg_replace('/(;q=.+)/i', '', strtolower(trim($_SERVER['HTTP_ACCEPT_CHARSET'])));

            $this->charsets = explode(',', $charsets);
        }

        if (count($this->charsets) == 0)
        {
            $this->charsets = array('Undefined');
        }
    }


    // --------------------------------------------------------------------

    /**
     * Is Browser
     *
     * @access    public
     * @return    bool
     */
    public function is_browser()
    {
        return $this->is_browser;
    }

    // --------------------------------------------------------------------

    /**
     * Is Robot
     *
     * @access    public
     * @return    bool
     */
    public function is_robot()
    {
        return $this->is_robot;
    }

    // --------------------------------------------------------------------

    /**
     * Is Mobile
     *
     * @access    public
     * @return    bool
     */
    public function is_mobile()
    {
        return $this->is_mobile;
    }

    // --------------------------------------------------------------------

    /**
     * Is this a referral from another site?
     *
     * @access    public
     * @return    bool
     */
    public function is_referral()
    {
        return ( ! isset($_SERVER['HTTP_REFERER']) OR $_SERVER['HTTP_REFERER'] == '') ? FALSE : TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Agent String
     *
     * @access    public
     * @return    string
     */
    public function agent_string()
    {
        return $this->agent;
    }

    // --------------------------------------------------------------------

    /**
     * Get Platform
     *
     * @access    public
     * @return    string
     */
    public function platform()
    {
        return $this->platform;
    }

    // --------------------------------------------------------------------

    /**
     * Get Browser Name
     *
     * @access    public
     * @return    string
     */
    public function browser()
    {
        return $this->browser;
    }

    // --------------------------------------------------------------------

    /**
     * Get the Browser Version
     *
     * @access    public
     * @return    string
     */
    public function version()
    {
        return $this->version;
    }

    // --------------------------------------------------------------------

    /**
     * Get The Robot Name
     *
     * @access    public
     * @return    string
     */
    public function robot()
    {
        return $this->robot;
    }
    // --------------------------------------------------------------------

    /**
     * Get the Mobile Device
     *
     * @access    public
     * @return    string
     */
    public function mobile()
    {
        return $this->mobile;
    }

    // --------------------------------------------------------------------

    /**
     * Get the referrer
     *
     * @access    public
     * @return    bool
     */
    public function referrer()
    {
        return ( ! isset($_SERVER['HTTP_REFERER']) OR $_SERVER['HTTP_REFERER'] == '') ? '' : trim($_SERVER['HTTP_REFERER']);
    }

    // --------------------------------------------------------------------

    /**
     * Get the accepted languages
     *
     * @access    public
     * @return    array
     */
    public function languages()
    {
        if (count($this->languages) == 0)
        {
            $this->_set_languages();
        }

        return $this->languages;
    }

    // --------------------------------------------------------------------

    /**
     * Get the accepted Character Sets
     *
     * @access    public
     * @return    array
     */
    public function charsets()
    {
        if (count($this->charsets) == 0)
        {
            $this->_set_charsets();
        }

        return $this->charsets;
    }

    // --------------------------------------------------------------------

    /**
     * Test for a particular language
     *
     * @access    public
     * @return    bool
     */
    public function accept_lang($lang = 'en')
    {
        return (in_array(strtolower($lang), $this->languages(), TRUE)) ? TRUE : FALSE;
    }

    // --------------------------------------------------------------------

    /**
     * Test for a particular character set
     *
     * @access    public
     * @return    bool
     */
    public function accept_charset($charset = 'utf-8')
    {
        return (in_array(strtolower($charset), $this->charsets(), TRUE)) ? TRUE : FALSE;
    }
    
}


/* End of file Agent.php */
/* Location: ./obullo/libraries/Agent.php */