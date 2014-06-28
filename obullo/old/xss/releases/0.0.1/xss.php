<?php

/**
 * Xss Class ( Security Helper )
 *
 * @package     packages
 * @subpackage  xss
 * @category    security
 * @link
 */

Class Xss {
    
    public function __construct()
    {
        global $logger;

        if( ! isset(getInstance()->xss))
        {
            getInstance()->xss = $this; // Make available it in the controller $this->xss->method();
        }

        $logger->debug('Xss Class Initialized');
    }

    // ------------------------------------------------------------------------

    /**
     * XSS Filtering
     *
     * @access	public
     * @param	string
     * @param	bool	whether or not the content is an image file
     * @return	string
     */
    public function clean($str, $is_image = false)
    {
        if(isset(getInstance()->security))
        {
            return getInstance()->security->xssClean($str, $is_image);
        }
        
        global $security;

        return $security->xssClean($str, $is_image);
    }

    // ------------------------------------------------------------------------

    /**
     * Sanitize Filename
     *
     * @access	public
     * @param	string
     * @return	string
     */
    public function sanitizeFilename($filename)
    {
        if(isset(getInstance()->security))
        {
            return getInstance()->security->sanitizeFilename($filename);
        }

        global $security;

        return $security->sanitizeFilename($filename);
    }

    // ------------------------------------------------------------------------

    /**
     * Strip Image Tags
     *
     * @access	public
     * @param	string
     * @return	string
     */
    public function stripImageTags($str)
    {
        $str = preg_replace("#<img\s+.*?src\s*=\s*[\"'](.+?)[\"'].*?\>#", "\\1", $str);
        $str = preg_replace("#<img\s+.*?src\s*=\s*(.+?).*?\>#", "\\1", $str);

        return $str;
    }

    // ------------------------------------------------------------------------

    /**
     * Convert PHP tags to entities
     *
     * @access	public
     * @param	string
     * @return	string
     */
    public function encodePhpTags($str)
    {
        return str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
    }
}
    
/* End of file xss.php */
/* Location: ./packages/xss/releases/0.0.1/xss.php */