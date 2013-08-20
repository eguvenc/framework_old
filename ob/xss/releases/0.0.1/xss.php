<?php
namespace xss {

    /**
     * XSS Helper
     *
     * @package     Xss filter
     * @subpackage  0.0.1
     * @version     0.0.1
     * @link
     */
    
    // ------------------------------------------------------------------------
    
    /**
     * XSS Filtering
     *
     * @access	public
     * @param	string
     * @param	bool	whether or not the content is an image file
     * @return	string
     */
    function clean($str, $is_image = false)
    {
        return Security::getInstance()->xssClean($str, $is_image);
    }

    // ------------------------------------------------------------------------

    /**
     * Sanitize Filename
     *
     * @access	public
     * @param	string
     * @return	string
     */
    function sanitizeFilename($filename)
    {
        return Security::getInstance()->sanitizeFilename($filename);
    }

    // ------------------------------------------------------------------------

    /**
     * Strip Image Tags
     *
     * @access	public
     * @param	string
     * @return	string
     */
    function stripImageTags($str)
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
    function encodePhpTags($str)
    {
        return str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
    }

}
    
/* End of file xss.php */
/* Location: ./ob/xss/releases/0.0.1/xss.php */
