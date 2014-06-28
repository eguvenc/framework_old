<?php

/**
* Post Class ( Fetch data from superglobal $_POST variable ).
*
* @package       packages
* @subpackage    get
* @category      request
* @link
* 
*/

Class Post
{
    /**
     * Constructor
     */
    public function __construct()
    {
        global $logger;

        if ( ! isset(getInstance()->post)) {
            getInstance()->post = $this; // Make available it in the controller $this->post->method();
        }
        $logger->debug('Post Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
    * Fetch an item from the POST array
    *
    * @access   public
    * @param    string
    * @param    bool
    * 
    * @return   string
    */
    public function get($index = null, $xss_clean = FALSE)
    {
        if ($index === null AND ! empty($_POST)) {  // Check if a field has been provided
            $post = array();
            foreach (array_keys($_POST) as $key) { // loop through the full _POST array
                $post[$key] = Get::fetchFromArray($_POST, $key, $xss_clean);
            }
            return $post;
        }
        return Get::fetchFromArray($_POST, $index, $xss_clean);
    }

}

// END post Class

/* End of file post.php */
/* Location: ./packages/post/releases/0.0.1/post.php */