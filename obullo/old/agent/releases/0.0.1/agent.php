<?php

/**
 * Identifies the platform, browser, robot, or mobile devise of the browsing agent
 *
 * @category  User_Agent
 * @package   Agent
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/package/agent
 */
Class Agent
{
    public $agent = null;
    public $is_browser = false;
    public $is_robot = false;
    public $is_mobile = false;
    public $languages = array();
    public $charsets = array();
    public $platforms = array();
    public $browsers = array();
    public $mobiles = array();
    public $robots = array();
    public $platform = array();
    public $browser = array();
    public $version = '';
    public $mobile = array();
    public $robot = array();

    /**
     * Constructor
     *
     * Sets the User Agent and runs the compilation routine
     *
     * @access    public
     * @return    void
     */
    public function __construct()
    {
        global $logger;

        if (!isset(getInstance()->agent)) {
            getInstance()->agent = $this; // Make available it in the controller $this->agent->method();
        }

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $this->agent = trim($_SERVER['HTTP_USER_AGENT']);
        }

        if (!is_null($this->agent)) {
            if ($this->_loadAgentFile()) {
                $this->_compileData();
            }
        }

        $logger->debug('Agent Class Initialized');
    }

    // ------------------------------------------------------------------------

    /**
     * Initialize to Class.
     * 
     * @return object
     */
    public function init()
    {
        return ($this);
    }

    // ------------------------------------------------------------------------

    /**
     * Compile the User Agent Data
     *
     * @access    private
     * @return    bool
     */
    public function _loadAgentFile()
    {
        $user_agents = getConfig('agents');  // obullo changes ..

        $return = false;

        if (isset($user_agents['platforms'])) {
            $this->platforms = &$user_agents['platforms'];
            unset($user_agents['platforms']);
            $return = true;
        }

        if (isset($user_agents['browsers'])) {
            $this->browsers = &$user_agents['browsers'];
            unset($user_agents['browsers']);
            $return = true;
        }

        if (isset($user_agents['mobiles'])) {
            $this->mobiles = &$user_agents['mobiles'];
            unset($user_agents['mobiles']);
            $return = true;
        }

        if (isset($user_agents['robots'])) {
            $this->robots = &$user_agents['robots'];
            unset($robots);
            $return = true;
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
    public function _compileData()
    {
        $this->_setPlatform();

        foreach (array('_setBrowser', '_setRobot', '_setMobile') as $function) {
            if ($this->$function() === true) {
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
    public function _setPlatform()
    {
        if (is_array($this->platforms) AND count($this->platforms) > 0) {
            foreach ($this->platforms as $key => $val) {
                if (preg_match("|" . preg_quote($key) . "|i", $this->agent)) {
                    $this->platform['key'] = $key;
                    $this->platform['val'] = $val;

                    return true;
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
    public function _setBrowser()
    {
        if (is_array($this->browsers) AND count($this->browsers) > 0) {
            foreach ($this->browsers as $key => $val) {
                if (preg_match("|" . preg_quote($key) . ".*?([0-9\.]+)|i", $this->agent, $match)) {
                    $this->is_browser = true;
                    $this->version = $match[1];

                    $this->browser['key'] = $key;
                    $this->browser['val'] = $val;

                    $this->_setMobile();
                    return true;
                }
            }
        }
        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Set the Robot
     *
     * @access    private
     * @return    bool
     */
    public function _setRobot()
    {
        if (is_array($this->robots) AND count($this->robots) > 0) {
            foreach ($this->robots as $key => $val) {
                if (preg_match("|" . preg_quote($key) . "|i", $this->agent)) {
                    $this->is_robot = true;
                    $this->robot['key'] = $key;
                    $this->robot['val'] = $val;

                    return true;
                }
            }
        }
        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Set the Mobile Device
     *
     * @access    private
     * @return    bool
     */
    public function _setMobile()
    {
        global $config;

        if (is_array($this->mobiles) AND count($this->mobiles) > 0) {
            foreach ($this->mobiles as $key => $val) {
                if (false !== (strpos(mb_strtolower($this->agent, $config['charset']), $key))) {
                    $this->is_mobile = true;
                    $this->mobile['key'] = $key;
                    $this->mobile['val'] = $val;

                    return true;
                }
            }
        }

        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Set the accepted languages
     *
     * @access    private
     * @return    void
     */
    public function _setLanguages()
    {
        if ((count($this->languages) == 0) AND isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) AND $_SERVER['HTTP_ACCEPT_LANGUAGE'] != '' AND is_string($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $languages = preg_replace('/(;q=[0-9\.]+)/i', '', strtolower(trim($_SERVER['HTTP_ACCEPT_LANGUAGE'])));

            $this->languages = explode(',', $languages);
        }

        if (count($this->languages) == 0) {
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
    public function _setCharsets()
    {
        if ((count($this->charsets) == 0) AND isset($_SERVER['HTTP_ACCEPT_CHARSET']) AND $_SERVER['HTTP_ACCEPT_CHARSET'] != '' AND is_string($_SERVER['HTTP_ACCEPT_CHARSET'])) {
            $charsets = preg_replace('/(;q=.+)/i', '', strtolower(trim($_SERVER['HTTP_ACCEPT_CHARSET'])));

            $this->charsets = explode(',', $charsets);
        }

        if (count($this->charsets) == 0) {
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
    public function isBrowser()
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
    public function isRobot()
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
    public function isMobile()
    {
        return $this->is_mobile;
    }

    // --------------------------------------------------------------------

    /**
     * Is this a referral from another site ?
     *
     * @access    public
     * @return    bool
     */
    public function isReferral()
    {
        return (!isset($_SERVER['HTTP_REFERER']) OR $_SERVER['HTTP_REFERER'] == '') ? false : true;
    }

    // --------------------------------------------------------------------

    /**
     * Agent String
     *
     * @access    public
     * @return    string
     */
    public function getAgent()
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
    public function getPlatform()
    {
        return $this->platform['val'];
    }

    // --------------------------------------------------------------------

    /**
     * Get Browser Name
     *
     * @access    public
     * @return    string
     */
    public function getBrowser()
    {
        return $this->browser['val'];
    }

    // --------------------------------------------------------------------

    /**
     * Get the Browser Version
     *
     * @access    public
     * @return    string
     */
    public function getBrowserVersion()
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
    public function getRobotName()
    {
        return $this->robot['val'];
    }

    // --------------------------------------------------------------------

    /**
     * Get the Mobile Device
     *
     * @access    public
     * @return    string
     */
    public function getMobileDevice()
    {
        return $this->mobile['val'];
    }

    // --------------------------------------------------------------------

    /**
     * Get the referrer
     *
     * @access    public
     * @return    bool
     */
    public function getReferrer()
    {
        return (!isset($_SERVER['HTTP_REFERER']) OR $_SERVER['HTTP_REFERER'] == '') ? '' : trim($_SERVER['HTTP_REFERER']);
    }

    // --------------------------------------------------------------------

    /**
     * Get the accepted languages
     *
     * @access    public
     * @return    array
     */
    public function getLanguages()
    {
        if (count($this->languages) == 0) {
            $this->_setLanguages();
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
    public function getCharsets()
    {
        if (count($this->charsets) == 0) {
            $this->_setCharsets();
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
    public function getAcceptLang($lang = 'en')
    {
        return (in_array(strtolower($lang), $this->getLanguages(), true)) ? true : false;
    }

    // --------------------------------------------------------------------

    /**
     * Test for a particular character set
     *
     * @access    public
     * @return    bool
     */
    public function getAcceptCharset($charset = 'utf-8')
    {
        return (in_array(strtolower($charset), $this->getCharsets(), true)) ? true : false;
    }

    // --------------------------------------------------------------------

    /**
     * Get The Key
     *
     * @access    public
     * @return    string
     */
    public function getKey($keyName = NULL)
    {
        $keyName = trim(strtolower($keyName));

        switch ($keyName) {
            case 'browser':
                return $this->browser['key'];
                break;

            case 'platform':
                return $this->platform['key'];
                break;

            case 'robot':
                return $this->robot['key'];
                break;

            case 'mobile':
                return $this->mobile['key'];
                break;
        }
    }

}

/* End of file Agent.php */
/* Location: ./packages/agent/releases/0.0.1/agent.php */