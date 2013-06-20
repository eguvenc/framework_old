<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         obullo
 * @author          obullo.com
 * @filesource
 * @license
 */

/**
 * Obullo Html Helper
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Html
 * @author      Obullo Team
 * @link
 */
// --------------------------------------------------------------------

/**
* Build css files in <head> tags
*
* css('welcome.css'); // current module
* css('subfolder/welcome.css') // from a subfolder
* css('../module/welcome.css');  // from /modules dir
* css(array('welcome.css', 'hello.css')); // array type
* css('#main {display: block; color: red;}', 'embed'); // inlise style
*
* @author   Obullo
* @param    string $href
* @param    string $title
* @param    string $media
* @param    string $rel
* @param    boolean $index_page
* @return   string
*/
if( ! function_exists('css') )
{
    function css($href, $title = '', $media = '', $rel = 'stylesheet', $index_page = FALSE)
    {
        $link = '<link ';

        if (is_array($href))
        {
            $ext = 'css';
            if(strpos($href, 'js/') === 0)
            {
                $ext  = 'js';
                $href = substr($href, 3);
            }
            
            $link = '';
                                
            foreach ($href as $v)
            {
                $link .= '<link ';

                $v = ltrim($v, '/');   // remove first slash  ( Obullo changes )

                if ( strpos($v, '://') !== FALSE)
                {
                    $link .= ' href="'. $v .'" ';
                }
                else
                {
                    $link .= ' href="'. _get_public_path($v, $extra_path = '', $ext) .'" ';
                }

                $link .= 'rel="'.$rel.'" type="text/css" ';

                if ($media    != '')
                {
                    $link .= 'media="'.$media.'" ';
                }

                if ($title    != '')
                {
                    $link .= 'title="'.$title.'" ';
                }

                $link .= "/>\n";
            }
        }
        else
        {                
            $ext = 'css';
            if(strpos($href, 'js/') === 0)
            {
                $ext  = 'js';
                $href = substr($href, 3);
            }
          
            $href = ltrim($href, '/');  // remove first slash

            if ( strpos($href, '://') !== FALSE)
            {
                $link .= ' href="'.$href.'" ';
            }
            elseif ($index_page === TRUE)
            {
                $link .= ' href="'. lib('ob/Config')->site_url($href, false) .'" ';
            }
            else
            {
                $link .= ' href="'. _get_public_path($href, $extra_path = '', $ext) .'" ';
            }

            $link .= 'rel="'.$rel.'" type="text/css" ';

            if ($media    != '')
            {
                $link .= 'media="'.$media.'" ';
            }

            if ($title    != '')
            {
                $link .= 'title="'.$title.'" ';
            }

            $link .= "/>\n";
        }

        return $link;
    }
}                       
// ------------------------------------------------------------------------

/**
* Build js files in <head> tags
*
* js('welcome.js');
* js('subfolder/welcome.js')
* js('../module/welcome.js');  from /modules dir
* js(array('welcome.js', 'hello.js'));
*
* @author   Obullo
* @param    string $src  it can be via a path
* @param    string $arguments
* @param    string $type
* @param    string $index_page load js dynamically
* @version  0.1
* @version  0.2 removed /js dir, added _get_public_path() func.
*
*/
if( ! function_exists('js') )
{
    function js($src, $arguments = '', $type = 'text/javascript', $index_page = FALSE)
    {
        $link = '<script type="'.$type.'" ';
        
        if (is_array($src))
        {
            $link = '';

            foreach ($src as $v)
            {
                $link .= '<script type="'.$type.'" ';

                $v = ltrim($v, '/');   // remove first slash  ( Obullo changes )

                if ( strpos($v, '://') !== FALSE)
                {
                    $link .= ' src="'. $v .'" ';
                }
                else
                {
                    $link .= ' src="'. _get_public_path($v, $extra_path = '') .'" ';
                }

                $link .= "></script>\n";
            }
        }
        else
        {
            $src = ltrim($src, '/');   // remove first slash

            if ( strpos($src, '://') !== FALSE)
            {
                $link .= ' src="'. $src .'" ';
            }
            elseif ($index_page === TRUE)  // .js file as PHP
            {
                $link .= ' src="'. lib('ob/Config')->site_url($src, false) .'" ';
            }
            else
            {
                $link .= ' src="'. _get_public_path($src, $extra_path = '') .'" ';
            }

            $link .= $arguments;
            $link .= "></script>\n";
        }

        return $link;

    }
}

// ------------------------------------------------------------------------

/**
 * Get configured output of the called plugin.
 * 
 * @param string $name plugin name (plugin folder)
 * @param string $config_file name of the plugin config file.
 * @return string
 */
if ( ! function_exists('plugin'))
{
    function plugin($plugin_name, $filename = 'plugins')
    {
        $config = lib('ob/Config');
        
        ##########
        
        $config->load($filename);
        
        ##########
        
        $plugins = $config->item($plugin_name);                         
                                            
        if(count($plugins) == 0)
        {
            return;
        }

        $output = '';
        foreach($plugins as $file_path)
        {
            $ext = substr(strrchr($file_path, '.'), 1);
            
            if($ext == 'js')
            {   
                if(strpos(ltrim($file_path, '/'), 'js') === 0)  // remove js word
                {
                    $file_path = substr(ltrim($file_path, '/'), 3);
                }
                
                $output.= js($file_path);
            }
            
            if($ext == 'css')
            {
                if(strpos(ltrim($file_path, '/'), 'css') === 0) // remove css word
                {
                   $file_path = substr(ltrim($file_path, '/'), 4);
                }
                
                $output.= css($file_path);
            }
        }
        
        return $output;
    }
}

// ------------------------------------------------------------------------

/**
* Generates a page document type declaration
*
* Valid options are xhtml11, xhtml-strict, xhtml-trans, xhtml-frame,
* html4-strict, html4-trans, and html4-frame.
*
* Values are saved in the doctypes config file.
*
* @access  public
*/
if( ! function_exists('doctype') )
{
    function doctype($type = 'xhtml1-strict')
    {
        return config($type, 'doctypes');
    }
}


// ------------------------------------------------------------------------

/**
* Image
*
* Generates an <img /> element
*
* @access   public
* @param    mixed    $src  sources folder image path via filename
* @param    boolean  $index_page
* @param    string   $attributes
* @version  0.1
* @version  0.2      added view_set_folder('img'); support
* @version  0.2      added $attributes variable
* @return   string
*/
if( ! function_exists('img') ) 
{
    function img($src = '', $attributes = '', $index_page = FALSE)
    {
        if ( ! is_array($src) )
        {
            $src = array('src' => $src);
        }

        $img = '<img';

        foreach ($src as $k => $v)
        {
            $v = ltrim($v, '/');   // remove first slash
            
            if ($k == 'src' AND strpos($v, '://') === FALSE)
            {
                if ($index_page === TRUE)
                {
                    $img .= ' src="'.lib('ob/Config')->site_url($v, false).'" ';
                }
                else
                {
                    $img .= ' src="' . _get_public_path($v, 'images'. $extra_path = '') .'" ';
                }
            }
            else
            {
                $img .= " $k=\"$v\" ";   // for http://
            }
        }

        $img .= $attributes . ' />';

        return $img;
    }
}

// ------------------------------------------------------------------------ 

/**
* Parse head files to learn whether it
* comes from modules directory.
*
* convert  this path ../welcome/welcome.css
* to /public_url/modules/welcome/public/css/welcome.css
*
* @author   CJ Lazell
* @author   Ersin Guvenc
* @access   private
* @param    mixed $file_url
* @param    mixed $extra_path
* @return   string | FALSE
*/
if( ! function_exists('_get_public_path') )
{
    function _get_public_path($file_url, $extra_path = '', $custom_extension = '')
    {                              
        $filename = $file_url;          
        $paths    = array();
        if( strpos($filename, '/') !== FALSE)
        {
            $paths      = explode('/', $filename);
            $filename   = array_pop($paths);
        }

        $sub_path   = '';
        if( count($paths) > 0)
        {
            $sub_path = implode('/', $paths) . '/';      // .module/public/css/sub/welcome.css  sub dir support
        }

        $ext = substr(strrchr($filename, '.'), 1);   // file extension
        if($ext == FALSE) 
        {
            return FALSE;
        }

        if($custom_extension != '') // set like this css('js/folder/theme/ui.css')
        {
            $ext = $custom_extension;
        }
        
        $folder = $ext . '/';
        
        if($extra_path != '')
        {
            $extra_path = trim($extra_path, '/').'/';
            $folder = '';
        }

        $config = lib('ob/Config');
        
        return $config->public_url('', true) .'assets/'. $extra_path . $folder . $sub_path . $filename;
    }
}

/* End of file html.php */
/* Location: ./obullo/helpers/html.php */