<?php

/**
 * Security AUTOLOADER
 * 
 * @param type $classname
 * @return type
 */
function Security_Autoload()
{    
    if(class_exists('Security'))
    { 
        return; 
    }
    
    $packages = get_config('packages');
    
    require(OB_MODULES .'security'. DS .'releases'. DS .$packages['dependencies']['security']['version']. DS .'src'. DS .'security'.EXT);
}

spl_autoload_register('Security_Autoload', true);

// ------------------------------------------------------------------------

/**
 * Obullo Security Helper
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Obullo Team
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
if ( ! function_exists('sanitize_filename'))
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

/* End of file security.php */
/* Location: ./ob_modules/security/releases/0.0.1/security.php */