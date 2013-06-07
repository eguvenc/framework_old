<?php
defined('BASE') or exit('Access Denied!'); 

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @license         public 
 * @since           Version 1.0
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Obullo Directory Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Obullo Team
 * @link        
 */

// ------------------------------------------------------------------------

/**
* Create a Directory Map
*
* Reads the specified directory and builds an array
* representation of it.  Sub-folders contained with the
* directory will be mapped as well.
*
* @access	public
* @param	string	path to source
* @param	int	    depth of directories to traverse (0 = fully recursive, 1 = current dir, etc)
* @return	array
*/
if( ! function_exists('directory_map') ) 
{
    function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE)
    {
        if ($fp = @opendir($source_dir))
        {
            $filedata    = array();
            $new_depth   = $directory_depth - 1;
            $source_dir  = rtrim($source_dir, DS).DS;        
                        
            while (FALSE !== ($file = readdir($fp)))
            {
                // Remove '.', '..', and hidden files [optional]
                if ( ! trim($file, '.') OR ($hidden == FALSE && $file[0] == '.'))
                {
                    continue;
                }

                if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir.$file))
                {
                    $filedata[$file] = directory_map($source_dir.$file.DS, $new_depth, $hidden);
                }
                else
                {
                    $filedata[] = $file;
                }
            }
            
            closedir($fp);
            return $filedata;
        }

        return FALSE;
    }
}
/* End of file directory.php */
/* Location: ./obullo/helpers/directory.php */