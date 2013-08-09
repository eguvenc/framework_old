<?php

/**
 * XSS Helper
 *
 * @package     Xss filter
 * @subpackage  0.0.1
 * @version     0.0.1
 * @link
 */

/**
 * XSS Filtering
 *
 * @access	public
 * @param	string
 * @param	bool	whether or not the content is an image file
 * @return	string
 */
if ( ! function_exists('xss_clean'))
{
    function xss_clean($str, $is_image = FALSE)
    {
        return Security::getInstance()->xss_clean($str, $is_image);
    }
}

// ------------------------------------------------------------------------

/**
 * Sanitize Filename
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('sanitize_filename'))
{
    function sanitize_filename($filename)
    {
        return Security::getInstance()->sanitize_filename($filename);
    }
}

// ------------------------------------------------------------------------

/**
 * Strip Image Tags
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('strip_image_tags'))
{
    function strip_image_tags($str)
    {
        $str = preg_replace("#<img\s+.*?src\s*=\s*[\"'](.+?)[\"'].*?\>#", "\\1", $str);
        $str = preg_replace("#<img\s+.*?src\s*=\s*(.+?).*?\>#", "\\1", $str);

        return $str;
    }
}

// ------------------------------------------------------------------------

/**
 * Convert PHP tags to entities
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('encode_php_tags'))
{
    function encode_php_tags($str)
    {
        return str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
    }
}

/* End of file xss_filter.php */
/* Location: ./ob/xss_filter/releases/0.0.1/xss_filter.php */