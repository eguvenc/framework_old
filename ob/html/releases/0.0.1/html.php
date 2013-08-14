<?php
namespace html {
    
    // --------------------------------------------------------------------
    
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

    Class start
    { 
        function __construct()
        {
            \log\me('debug', 'Html Helper Initialized.');
        }
    }
    
    /**
    * Build css files.
    *
    * css('welcome.css');
    * css(array('welcome.css', 'hello.css')); // array type
    *
    * @author   Obullo
    * @param    string $href
    * @param    string $title
    * @param    string $media
    * @param    string $rel
    * @param    boolean $index_page
    * @return   string
    */
    function css($href, $title = '', $media = '', $rel = 'stylesheet', $index_page = false)
    {
        if(strpos($href, '/*') !== false)   // WE UNDERSTAND THIS IS A FOLDER 
        {
            $href   = substr($href, 0, -2);
            $files  = '';
            $folder = getFilenames(ROOT .'assets'. DS .'css'. DS . str_replace('/', DS, $href));
            
            foreach ($folder as $filename)
            {
                $files .= _css($href.'/'.$filename, $title, $media, $rel, $index_page = false);
            }

            return $files;
        }

        return _css($href, $title, $media, $rel, $index_page);
    }

    // ------------------------------------------------------------------------

    /**
    * Build css files.
    *
    * css('welcome.css');
    * css(array('welcome.css', 'hello.css')); // array type
    *
    * @author   Obullo
    * @param    string $href
    * @param    string $title
    * @param    string $media
    * @param    string $rel
    * @param    boolean $index_page
    * @return   string
    */
    function js($src, $arguments = '', $type = 'text/javascript', $index_page = false)
    {
        if(strpos($src, '/*') !== false)  // WE UNDERSTAND THIS IS A FOLDER 
        {
            $src    = substr($src, 0, -2);
            $files  = '';
            $folder = getFilenames(ROOT .'assets'. DS .'js'. DS . str_replace('/', DS, $src));
            
            foreach ($folder as $filename)
            {
                $files .= _js($src.'/'.$filename, $arguments, $type, $index_page = false);
            }

            return $files;
        }

        return _js($src, $arguments, $type, $index_page);
    }

    // ------------------------------------------------------------------------

    /**
    * Build css files private function.
    */
    function _css($href, $title = '', $media = '', $rel = 'stylesheet', $index_page = false)
    {
        $link = '<link ';           
        $ext  = 'css';
        if(strpos($href, 'js/') === 0)
        {
            $ext  = 'js';
            $href = substr($href, 3);
        }

        $href = ltrim($href, '/');  // remove first slash

        if ( strpos($href, '://') !== false)
        {
            $link .= ' href="'.$href.'" ';
        }
        elseif ($index_page === true)
        {
            $link .= ' href="'. getInstance()->config->siteUrl($href, false) .'" ';
        }
        else
        {
            $link .= ' href="'. _getAssetPath($href, $extra_path = '', $ext) .'" ';
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
        
        return $link;
    }         

    // ------------------------------------------------------------------------

    /**
    * Build js files private function.
    */
    function _js($src, $arguments = '', $type = 'text/javascript', $index_page = false)
    {
        $link = '<script type="'.$type.'" ';        
        $src  = ltrim($src, '/');   // remove first slash

        if ( strpos($src, '://') !== false)
        {
            $link .= ' src="'. $src .'" ';
        }
        elseif ($index_page === true)  // .js file as PHP
        {
            $link .= ' src="'. getInstance()->config->siteUrl($src, false) .'" ';
        }
        else
        {
            $link .= ' src="'. _getAssetPath($src, $extra_path = '', 'js') .'" ';
        }

        $link .= $arguments;
        $link .= "></script>\n";
       
        return $link;
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
    * @return   string
    */
    function img($src = '', $attributes = '', $index_page = false)
    {
        if ( ! is_array($src) )
        {
            $src = array('src' => $src);
        }

        $img = '<img';

        foreach ($src as $k => $v)
        {
            $v = ltrim($v, '/');   // remove first slash
            
            if ($k == 'src' AND strpos($v, '://') === false)
            {
                if ($index_page === true)
                {
                    $img .= ' src="'. getInstance()->config->siteUrl($v, false).'" ';
                }
                else
                {
                    $img .= ' src="' . _getAssetPath($v, 'images'. $extra_path = '') .'" ';
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

    // ------------------------------------------------------------------------ 

    /**
    * Get assets directory path
    *
    * @access   private
    * @param    mixed $file_url
    * @param    mixed $extra_path
    * @return   string | false
    */
    function _getAssetPath($file_url, $extra_path = '', $ext = '')
    {                              
        $filename = $file_url;          
        $paths    = array();
        if( strpos($filename, '/') !== false)
        {
            $paths      = explode('/', $filename);
            $filename   = array_pop($paths);
        }

        $sub_path   = '';
        if( count($paths) > 0)
        {
            $sub_path = implode('/', $paths) . '/';      // .module/public/css/sub/welcome.css  sub dir support
        }
        
        $folder = $ext . '/';
        
        if($extra_path != '')
        {
            $extra_path = trim($extra_path, '/').'/';
            $folder = '';
        }

        $config = getInstance()->config;
        
        return $config->assetUrl('', true) .'assets/'. $extra_path . $folder . $sub_path . $filename;
    }

    // ------------------------------------------------------------------------

    /**
    * Get folder filenames.
    * 
    * @staticvar array $_filedata
    * @param string $source_dir
    * @param bool $include_path
    * @param bool $_recursion
    * @return boolean | array
    */
    function getFilenames($source_dir, $include_path = false, $_recursion = false)
    {
        static $_filedata = array();

        if ($fp = @opendir($source_dir))
        {
            // reset the array and make sure $source_dir has a trailing slash on the initial call
            if ($_recursion === false)
            {
                $_filedata = array();
                $source_dir = rtrim(realpath($source_dir), DS). DS;
            }

            while (false !== ($file = readdir($fp)))
            {
                if (@is_dir($source_dir.$file) && strncmp($file, '.', 1) !== 0)
                {
                            getFilenames($source_dir.$file. DS, $include_path, true);
                }
                elseif (strncmp($file, '.', 1) !== 0)
                {
                        $_filedata[] = ($include_path == true) ? $source_dir.$file : $file;
                }
            }
            
            return $_filedata;
        }
        else
        {
            return false;
        }
    }

}

/* End of file html.php */
/* Location: ./ob/html/releases/0.0.1/html.php */