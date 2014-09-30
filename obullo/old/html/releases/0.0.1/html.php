<?php

/**
* Html Class
*
* @package       packages
* @subpackage    html
* @category      html
* @link
*/

Class Html {
    
    public function __construct()
    {
        global $logger;

        if( ! isset(getInstance()->html)) {
            getInstance()->html = $this; // Make available it in the controller $this->html->method();
        }
        $logger->debug('Html Class Initialized');
    }
    // --------------------------------------------------------------------
    
    /**
    * Build css files.
    *
    * @param    string $href
    * @param    string $tit title or $sort of directory list
    * @param    string $media
    * @param    string $rel
    * @param    boolean $index_page
    * @return   string
    */
    public function css($href, $tit = '', $media = '', $rel = 'stylesheet', $index_page = false)
    {
        $title = is_string($tit) ? $tit : ''; // is reverse sort true ?

        if(strpos($href, '/*') !== false)   // Is it folder ?
        {
            $files      = '';
            $exp        = explode('/*', $href);
            $data       = $this->_parseRegex($href, $exp);
            $source_dir = ASSETS.'css'. DS . str_replace('/', DS, $exp[0]);

            foreach (scandir($source_dir, ($tit === true) ? 1 : 0) as $filename)
            {   
                if(pathinfo($source_dir.$filename, PATHINFO_EXTENSION) == 'css')
                {
                    if(count($data['includeFiles']) > 0 AND in_array($filename, $data['includeFiles']))
                    {
                        $files .= $this->_css($exp[0].'/'.$filename, $title, $media, $rel, $index_page = false);
                    }
                    if(count($data['excludeFiles']) > 0 AND ! in_array($filename, $data['excludeFiles']))
                    {
                        $files .= $this->_css($exp[0].'/'.$filename, $title, $media, $rel, $index_page = false);
                    }
                    if(count($data['includeFiles']) == 0 AND count($data['excludeFiles']) == 0)
                    {
                        $files .= $this->_css($exp[0].'/'.$filename, $title, $media, $rel, $index_page = false);
                    }
                }
            }
            return $files;
        }
        return $this->_css($href, $title, $media, $rel, $index_page);
    }

    // ------------------------------------------------------------------------

    /**
     * Build css link
     * 
     * @param  string  $href       
     * @param  string  $title      
     * @param  string  $media     
     * @param  string  $rel        
     * @param  boolean $index_page 
     * @return string             
     */
    private function _css($href, $title = '', $media = '', $rel = 'stylesheet', $index_page = false)
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
            $link .= ' href="'. getInstance()->uri->getSiteUrl($href, false) .'" ';
        }
        else
        {
            $link .= ' href="'. self::_getAssetPath($href, $extra_path = '', $ext) .'" ';
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

        $link = str_replace(DS, '/', $link);
        
        return $link;
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
    public static function _getAssetPath($file, $extra_path = '', $ext = '')
    {                                      
        $paths = array();
        if( strpos($file, '/') !== false)
        {
            $paths = explode('/', $file);
            $file  = array_pop($paths);
        }

        $sub_path   = '';
        if( count($paths) > 0)
        {
            $sub_path = implode('/', $paths) . '/';      // .assets/css/sub/welcome.css  sub dir support
        }
        
        $folder = $ext . '/';
        if($extra_path != '')
        {
            $extra_path = trim($extra_path, '/').'/';
            $folder     = '';
        }
        
        $assets_url = str_replace(DS, '/', ASSETS);
        $assets_url = str_replace(ROOT, '', ASSETS);

        return getInstance()->uri->getAssetsUrl('', false) .$assets_url. $extra_path . $folder . $sub_path . $file;
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
    public function img($src = '', $attributes = '', $index_page = false)
    {
        if ( ! is_array($src) )
        {
            $src = array('src' => $src);
        }

        $img = '<img';

        foreach ($src as $k => $v)
        {
            $v = ltrim($v, '/');   // remove first slash
            
            if ($k == 'src' AND strpos($v, '://') === false) {
                if ($index_page === true) {
                    $img .= ' src="'. getInstance()->uri->getSiteUrl($v, false).'" ';
                }
                else
                {
                    $img .= ' src="' . self::_getAssetPath($v, 'images'. $extra_path = '') .'" ';
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
    * Build js files.
    *
    * @param    string $href
    * @param    mixed $args js arguments or $sort of directory list
    * @param    string $media
    * @param    string $rel
    * @param    boolean $index_page
    * @return   string
    */
    public function js($src, $args = '', $type = 'text/javascript', $index_page = false)
    {
        $arguments = is_string($args) ? $args : '';  // is reverse sort true ?

        if (strpos($src, '/*') !== false) {  // Is it folder ?
            $files      = '';
            $exp        = explode('/*', $src);
            $data       = getInstance()->html->_parseRegex($src, $exp);
            $source_dir = ASSETS .'js'. DS . str_replace('/', DS, $exp[0]);

            foreach (scandir($source_dir, ($args === true) ? 1 : 0) as $filename) {   
                if(pathinfo($source_dir.$filename, PATHINFO_EXTENSION) == 'js')
                {
                    if ( count($data['includeFiles']) > 0 AND in_array($filename, $data['includeFiles'])) {
                        $files .= $this->_js($exp[0].'/'.$filename, $arguments, $type, $index_page = false);
                    }
                    if ( count($data['excludeFiles']) > 0 AND ! in_array($filename, $data['excludeFiles'])) {
                        $files .= $this->_js($exp[0].'/'.$filename, $arguments, $type, $index_page = false);
                    }
                    if (count($data['includeFiles']) == 0 AND count($data['excludeFiles']) == 0) {
                        $files .= $this->_js($exp[0].'/'.$filename, $arguments, $type, $index_page = false);
                    }
                }
            }
            return $files;
        }
        return $this->_js($src, $arguments, $type, $index_page);
    }

    // ------------------------------------------------------------------------

    /**
     * Build js link
     * 
     * @param  string  $src       
     * @param  string  $arguments 
     * @param  string  $type       
     * @param  boolean $index_page
     * @return string             
     */
    private function _js($src, $arguments = '', $type = 'text/javascript', $index_page = false)
    {
        $link = '<script type="'.$type.'" ';        
        $src  = ltrim($src, '/');   // remove first slash

        if ( strpos($src, '://') !== false)
        {
            $link .= ' src="'. $src .'" ';
        }
        elseif ($index_page === true)  // .js file as PHP
        {
            $link .= ' src="'. getInstance()->uri->getSiteUrl($src, false) .'" ';
        }
        else
        {
            $link .= ' src="'. self::_getAssetPath($src, $extra_path = '', 'js') .'" ';
        }

        $link .= $arguments;
        $link .= "></script>\n";
       
        $link = str_replace(DS, '/', $link);
        return $link;
    }

    // ------------------------------------------------------------------------ 

    /**
     * Parse Regex Css and Js source.
     * 
     * @param  string $src regex string
     * @param  array $exp explode array
     * @return array
     */
    private function _parseRegex($src, $exp)
    {
        $data = array();
        $data['includeFiles'] = array();
        $data['excludeFiles'] = array();

        if(strpos($exp[1], '^(') === 0)  // remove unwanted files
        {
            $matches = array();
            if(preg_match('|\^\((.*)\)|', $src, $matches))
            {
                $data['excludeFiles'] = explode('|', $matches[1]);
            }
        }
        elseif(strpos($exp[1], '(') === 0)
        {
            $matches = array();
            if(preg_match('|\((.*)\)|', $src, $matches))
            {
                $data['includeFiles'] = explode('|', $matches[1]);
            }
        }

        return $data;
    }

}

/* End of file html.php */
/* Location: ./packages/html/releases/0.0.1/html.php */