<?php

/**
* GET Class ( Fetch data from superglobal $_GET variable ).
*
* @package       packages
* @subpackage    get
* @category      request
* @link
* 
*/

Class Get
{
    /**
     * Constructor
     */
    public function __construct()
    {
        global $logger;

        if( ! isset(getInstance()->get)) {
            getInstance()->get = $this; // Make available it in the controller $this->get->method();
        }
        $logger->debug('Get Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
    * Fetch an item from the GET array
    *
    * @access   public
    * @param    string
    * @param    bool
    * 
    * @return   string
    */
    public function get($index = null, $xss_clean = false)
    {
        if ($index === null AND ! empty($_GET)) {  // Check if a field has been provided
            $get = array();
            foreach (array_keys($_GET) as $key) { // loop through the full _GET array
                $get[$key] = self::fetchFromArray($_GET, $key, $xss_clean);
            }
            return $get;
        }
        return self::fetchFromArray($_GET, $index, $xss_clean);
    }

    // --------------------------------------------------------------------

    /**
    * Fetch from array
    *
    * This is a helper function to retrieve values from global arrays
    *
    * @access   public
    * @param    $method string
    * @param    $array array
    * @param    $index string
    * @param    $xss_clean bool
    * @return   string
    */
    public static function fetchFromArray(&$array, $index = '', $xss_clean = false)
    {
        if ( ! isset($array[$index])) {
            return false;
        }
        if ($xss_clean) {
            if (isset(getInstance()->security)) {
                return getInstance()->security->xssClean($array[$index]);
            }
            $security = new Security;
            return $security->xssClean($array[$index]);
        }
        return $array[$index];
    }

}

// END Get Class

/* End of file get.php */
/* Location: ./packages/get/releases/0.0.1/get.php */