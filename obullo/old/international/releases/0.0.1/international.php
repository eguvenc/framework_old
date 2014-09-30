<?php

// http://us.php.net/manual/tr/book.intl.php

/**
 * International ( Localization ) Class
 * This class sets the accepted language code ( en-US )
 * charsets , language name and other things.
 *
 * @package       packages
 * @subpackage    localization
 * @category      locale
 * @link
 */
Class International
{
    public $langCode;
    public $langName;
    public $langArray;
    public $langKey = 'langCode'; // Language key of the lang code default is = "langCode"
    // Uri query string based example:  http://example.com/home?langCode=en
    // Uri segment based example :      http://example.com/home/en
    private $cookie_prefix = 'intl_';

    // ------------------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct()
    {
        global $config, $logger;

        $this->config = getConfig('international');  // get package config file

        if (!extension_loaded('intl')) {
            throw new Exception(sprintf(
                    '%s package requires the intl PHP extension', __CLASS__
            ));
        }

        if (!isset(getInstance()->international)) {
            getInstance()->international = $this;  // Make available it in the controller $this->language->method();
        }

        //------------ Set cookie prefix -------------//

        $this->cookie_prefix = $this->config['cookie_prefix'];

        //----------------------------------------------

        if ($this->config['enable_query_string']['enabled']) {  // set language key
            $this->langKey = $this->config['enable_query_string']['key'];
        } elseif ($this->config['enable_uri_segment']['enabled']) {
            $this->langKey = $this->config['enable_uri_segment']['key'];
        }

        $this->langArray = $this->config['languages'];
        $this->langCode = $config['default_translation']; // default lang code
        $this->langName = $this->langArray[$this->langCode];   // default lang name

        $logger->debug('International Class Initialized');

        $this->_init(); // Initialize 
    }

    // ------------------------------------------------------------------------

    /**
     * Initialize Function
     * 
     * @return void
     */
    public function _init()
    {
        global $config;

        if (defined('STDIN')) { // Disable console & task errors
            return;
        }

        //----------- SET FROM HTTP GET METHOD ------------//

        if ($this->config['enable_query_string']['enabled'] AND isset($_GET[$this->langKey])) { // check $_GET
            $this->setLanguage($_GET[$this->langKey]);
            return;
        }

        //----------- SET FROM URI SEGMENT ------------//

        if ($this->config['enable_uri_segment']['enabled'] AND is_numeric($this->config['enable_uri_segment']['segment'])) { // check uri segment
            $segment = getInstance()->uri->getSegment($this->config['enable_uri_segment']['segment']);

            if (!empty($segment)) { // empty control
                $this->setLanguage($segment);
                return;
            }
        }

        //----------- IF WE HAVE THE COOKIE RETURN FALSE ------------//

        $cookie_name = $this->cookie_prefix . $this->langKey;

        if (isset($_COOKIE[$cookie_name])) {  // check cookie if we have not lang cookie
            $this->setLanguage($_COOKIE[$cookie_name], false); // DO NOT WRITE TO COOKIE JUST SET TO VARIABLES
            return;
        }

        //----------- SET FROM BROWSER DEFAULT VALUE ------------// 

        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $this->setLanguage(locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']));
            return;
        }

        $this->setLanguage($config['default_translation']); // Set from framework config file
    }

    // ------------------------------------------------------------------------

    /**
     * Set language code
     * 
     * @param string $langCode
     */
    private function _setLangCode($langCode = 'en_US')
    {
        $this->langCode = (string) $langCode;
    }

    // ------------------------------------------------------------------------

    /**
     * Set language name using lang code
     *
     * @return  void
     */
    private function _setLangName()
    {
        if (isset($this->langArray[$this->langCode])) {
            $this->langName = $this->langArray[$this->langCode];
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Set language code
     * 
     * @param string $langCode
     */
    public function setLanguage($langCode = 'en_US', $write_cookie = true)
    {
        if (!isset($this->langArray[$langCode])) { // If its not in defined languages.
            return; // Good bye ..
        }

        $this->_setLangCode($langCode);
        $this->_setLangName();

        if ($write_cookie) {
            $this->_setCookie(); // write to cookie
        }

        if ($this->config['enable_locale_set_default']) {  // use locale_set_default function ?
            locale_set_default($this->getLangCode());  // http://www.php.net/manual/bg/function.locale-set-default.php
        }
    }

    // -----------------------------------------------------------------------

    /**
     * Get current langName
     * 
     * @return string
     */
    public function getLangName()
    {
        if (isset($this->langArray[$this->langCode])) {
            return $this->langArray[$this->langCode];
        }

        return $this->langName;
    }

    // -----------------------------------------------------------------------

    /**
     * Get curent langCode
     * 
     * @return string
     */
    public function getLangCode()
    {
        return $this->langCode;
    }

    // -----------------------------------------------------------------------

    /**
     * Set cookie to lang code
     * 
     * @param string $langCode
     */
    private function _setCookie()
    {
        global $config;

        $this->cookie_domain = (!empty($this->config['cookie_domain'])) ? $this->config['cookie_domain'] : $config['cookie_domain'];
        $this->cookie_path = (!empty($this->config['cookie_path'])) ? $this->config['cookie_path'] : $config['cookie_path'];
        $this->expiration = $this->config['cookie_expire'];

        // Set the cookie
        setcookie($this->cookie_prefix . $this->langKey, $this->getLangName(), $this->expiration, $this->cookie_path, $this->cookie_domain, 0);
    }

}

/* End of file international.php */
/* Location: ./packages/international/releases/0.0.1/international.php */