<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @copyright       Ersin Guvenc (c) 2009.
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Obullo Pager Common File
 *
 *
 * @package       Obullo
 * @subpackage    Libraries.drivers.Pager_common
 * @category      Libraries
 * @author        Ersin Guvenc
 * @author        Derived from PEAR Pager package.
 * @see           Original package http://pear.php.net/package/Pager
 * @link          
 */

Class OB_Pager_common
{
    public $_total_items;               // integer number of items
    public $_per_page       = 10;       // integer number of items per page
    public $_delta          = 10;       // integer number of page links for each window
    public $_current_page   = 1;        // integer current page number
    public $_total_pages    = 1;        // integer total pages number
    public $_link_class     = '';       // string CSS class for links
    public $_class_string   = '';       // string wrapper for CSS class name
    public $_base_url       = '';       // string base_url  // pear pager $_path / PAGER_CURRENT_PATHNAME
    public $_filename       = '';       // string file name
    public $_fix_filename   = TRUE;     // boolean If false, don't override the fileName option. Use at your own risk.
    public $_http_method    = 'GET';    // string specifies which HTTP method to use
    public $_form_id        = '';       // string specifies which HTML form to use
    public $_import_query   = TRUE;     // boolean whether or not to import submitted data
    public $_query_string   = TRUE;     // boolean whether or not to import submitted data
    public $_url_var        = 'page';   // string name of the querystring var for page
    public $_link_data      = array();  // array data to pass through the link
    public $_extra_vars     = array();  // array additional URL vars
    public $_exclude_vars   = array();  // array URL vars to ignore
    public $_expanded       = TRUE;     // boolean TRUE => expanded mode (for Pager_Sliding)
    public $_accesskey      = FALSE;    // boolean TRUE => show accesskey attribute on <a> tags
    public $_attributes     = '';       // string extra attributes for the <a> tag
    public $_onclick        = '';       // string onclick
     
    public $_alt_first      = 'first page';       // string alt text for "first page" (use "%d" placeholder for page number)
    public $_alt_prev       = 'previous page';    // string alt text for "previous page"
    public $_alt_next       = 'next page';        // string alt text for "next page"
    public $_alt_last       = 'last page';        // string alt text for "last page" (use "%d" placeholder for page number)
    public $_alt_page       = 'page';             // string alt text for "page" (use optional "%d" placeholder for page number)
    
    public $_prev_img       = '&lt;&lt; Back';    // string image/text to use as "prev" link
    public $_prev_img_empty = NULL;               // image/text to use as "prev" link when no prev link is needed  (e.g. on the first page)
    public $_next_img       = 'Next &gt;&gt;';    // string image/text to use as "next" link
    public $_next_img_empty = NULL;               // image/text to use as "next" link when no next link is needed (e.g. on the last page)
                                                  // NULL deactivates it
                                                
    public $_separator                = '';        // string link separator
    public $_spaces_before_separator  = 0;         // integer number of spaces before separator
    public $_spaces_after_separator   = 1;         // integer number of spaces after separator
    public $_cur_page_link_class      = '';        // string CSS class name for current page link
    public $_cur_page_span_pre        = '';        // string Text before current page link
    public $_cur_page_span_post       = '';        // string Text after current page link
    public $_first_page_pre           = '[';       // string Text before first page link
    public $_first_page_text          = '';        // string Text to be used for first page link
    public $_first_page_post          = ']';       // string Text after first page link
    public $_last_page_pre            = '[';       // string Text before last page link
    public $_last_page_text           = '';        // string Text to be used for last page link
    public $_last_page_post           = ']';       // string Text after last page link
    public $_spaces_before            = '';        // string Will contain the HTML code for the spaces
    public $_spaces_after             = '';        // string Will contain the HTML code for the spaces
    
    public $_first_link_title      = 'first page';      // string String used as title in <link rel="first"> tag
    public $_next_link_title       = 'next page';       // string String used as title in <link rel="next"> tag
    public $_prev_link_title       = 'previous page';   //  string String used as title in <link rel="previous"> tag
    public $_last_link_title       = 'last page';       // string String used as title in <link rel="last"> tag

    public $_show_all_text         = '';        // string Text to be used for the 'show all' option in the select box
    public $_item_data             = NULL;      // array data to be paged
    public $_clear_if_void         = TRUE;      // boolean If TRUE and there's only one page, links aren't shown
    public $_use_sessions          = FALSE;     // boolean Use session for storing the number of items per page
    public $_close_session         = FALSE;     // boolean Close the session when finished reading/writing data
    public $_session_var           = 'set_per_page';  // string name of the session var for number of items per page
    
    public $links                  = '';        // string Complete set of links
    public $link_tags              = '';        // string Complete set of link tags
    public $link_tags_raw          = array();   // array Complete set of raw link tags
    public $range                  = array();   // array Array with a key => value pair representing
                                                // page# => bool value (true if key==currentPageNumber).
                                                // can be used for extreme customization.

    public $_allowed_options = array(           // array list of available options (safety check)
        'total_items',
        'per_page',
        'delta',
        'link_class',
        'base_url',      // 'path',  ( Obullo changes .. )
        'filename',
        'fix_filename',
        'http_method',
        'query_string',  // ( Obullo changes .. )
        'form_id',
        'import_query',
        'url_var',
        'alt_first',
        'alt_prev',
        'alt_next',
        'alt_last',
        'alt_page',
        'prev_img',
        'prev_img_empty',
        'next_img',
        'next_img_empty',
        'expanded',
        'accesskey',
        'attributes',
        'onclick',
        'separator',
        'spaces_before_separator',
        'spaces_after_separator',
        'cur_page_link_class',
        'cur_page_span_pre',
        'cur_page_span_post',
        'first_page_pre',
        'first_page_text',
        'first_page_post',
        'last_page_pre',
        'last_page_text',
        'last_page_post',
        'first_link_title',
        'next_link_title',
        'prev_link_title',
        'last_link_title',
        'show_all_text',
        'item_data',
        'clear_if_void',
        'use_sessions',
        'close_session',
        'session_var',
        'extra_vars',
        'exclude_vars',
        'current_page',
    );

    // ------------------------------------------------------------------------
    
    /**
    * Generate or refresh the links and paged data after a call to set_options()
    *
    * @access public
    * @return void
    */
    public function build()
    {
        $this->_page_data    = array();   // reset
        $this->links         = '';
        $this->link_tags     = '';
        $this->link_tags_raw = array();

        $this->_generate_page_data();
        $this->_set_first_last_text();

        if ($this->_total_pages > (2 * $this->_delta + 1)) 
        {
            $this->links .= $this->_print_first_page();
        }

        $this->links .= $this->_get_back_link();
        $this->links .= $this->_get_page_links();
        $this->links .= $this->_get_next_link();

        $this->link_tags .= $this->_get_first_link_tag();
        $this->link_tags .= $this->_get_prev_link_tag();
        $this->link_tags .= $this->_get_next_link_tag();
        $this->link_tags .= $this->_get_last_link_tag();
        
        $this->link_tags_raw['first'] = $this->_get_first_link_tag(true);
        $this->link_tags_raw['prev']  = $this->_get_prev_link_tag(true);
        $this->link_tags_raw['next']  = $this->_get_next_link_tag(true);
        $this->link_tags_raw['last']  = $this->_get_last_link_tag(true);
        
        if ($this->_total_pages > (2 * $this->_delta + 1)) 
        {
            $this->links .= $this->_print_last_page();
        }
    }

    // ------------------------------------------------------------------------
    
    /**
    * Returns an array of current pages data
    *
    * @param integer $page_id Desired page ID (optional)
    *
    * @return array Page data
    * @access public
    */
    public function get_page_data($page_id = NULL)
    {
        $page_id = empty($page_id) ? $this->_current_page : $page_id;

        if ( ! isset($this->_page_data)) 
        {
            $this->_generate_page_data();
        }
        
        if ( ! empty($this->_page_data[$page_id])) 
        {
            return $this->_page_data[$page_id];
        }
        
        return array();
    }

    // ------------------------------------------------------------------------
    
    /**
    * Returns offsets for given page_id. Eg, if you
    * pass it page_id one and your perPage limit is 10 it will return (1, 10). page_id of 2 would
    * give you (11, 20).
    *
    * @param integer $page_id page_id to get offsets for
    * @return array  First and last offsets
    * @access public
    */
    public function get_offset_by_page($page_id = NULL)
    {
        $page_id = isset($page_id) ? $page_id : $this->_current_page;
        
        if ( ! isset($this->_page_data)) 
        {
            $this->_generate_page_data();
        }

        if (isset($this->_page_data[$page_id]) OR is_null($this->_item_data)) 
        {
            return array(
                        max(($this->_per_page * ($page_id - 1)) + 1, 1),
                        min($this->_total_items, $this->_per_page * $page_id)
                   );
        }
        return array(0, 0);
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Returns ID of current page
     *
     * @return integer ID of current page
     * @access public
     */
    public function get_current_page()
    {
        return $this->_current_page;
    }

    // ------------------------------------------------------------------------

    /**
     * Returns next page ID. If current page is last page
     * this function returns FALSE
     *
     * @return mixed Next page ID or false
     * @access public
     */
    public function get_next_page()
    {
        return ($this->get_current_page() == $this->num_pages() ? false : $this->get_current_page() + 1);
    }

    // ------------------------------------------------------------------------
    
    /**
     * Returns previous page ID. If current page is first page
     * this function returns FALSE
     *
     * @return mixed Previous page ID or false
     * @access public
     */
    public function get_prev_page()
    {
        return $this->is_first_page() ? false : $this->get_current_page() - 1;
    }

    // ------------------------------------------------------------------------
    
    /**
    * Returns number of items
    *
    * @return integer Number of items
    * @access public
    */
    public function num_items()
    {
        return $this->_total_items;
    }

    // ------------------------------------------------------------------------

    /**
    * Returns number of pages
    *
    * @return integer Number of pages
    * @access public
    */
    public function num_pages()
    {
        return (int)$this->_total_pages;
    }

    // ------------------------------------------------------------------------

    /**
    * Returns whether current page is first page
    *
    * @return bool First page or not
    * @access public
    */
    public function is_first_page()
    {
        return ($this->_current_page < 2);
    }

    // ------------------------------------------------------------------------

    /**
    * Returns whether current page is last page
    *
    * @return bool Last page or not
    * @access public
    */
    public function is_last_page()
    {
        return ($this->_current_page == $this->_total_pages);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Returns whether last page is complete
    *
    * @return bool Last page complete or not
    * @access public
    */
    public function is_last_page_end()
    {
        return !($this->_total_items % $this->_per_page);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Calculates all page data
    *
    * @return void
    * @access private
    */
    public function _generate_page_data()
    {   
        if ( ! is_null($this->_item_data))  // Been supplied an array of data?
        {
            $this->_total_items = count($this->_item_data);
        }
        
        $this->_total_pages = ceil((float)$this->_total_items / (float)$this->_per_page);
        
        $i = 1;
        if ( ! empty($this->_item_data)) 
        {
            foreach ($this->_item_data as $key => $value) 
            {
                $this->_page_data[$i][$key] = $value;
                if (count($this->_page_data[$i]) >= $this->_per_page) 
                {
                    ++$i;
                }
            }
        } 
        else 
        {
            $this->_page_data = array();
        }

        //prevent URL modification
        $this->_current_page = min($this->_current_page, $this->_total_pages);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Renders a link using the appropriate method
    *
    * @param string $altText  Alternative text for this link (title property)
    * @param string $linkText Text contained by this link
    *
    * @return string The link in string form
    * @access private
    */
    public function _render_link($alt_text, $link_text)
    {
        $OB = this();
        
        if ($this->_http_method == 'GET') 
        {
            $href = '?' . $this->_http_build_query_wrapper($this->_link_data);

            // Is query string false, use Obullo style urls  ( Obullo Changes )
            if($this->_query_string == FALSE)
            {   
                 $href = $this->_link_data[$this->_url_var]; 
            }
            
            $onclick = '';
            if (array_key_exists($this->_url_var, $this->_link_data)) 
            {
                $onclick = str_replace('%d', $this->_link_data[$this->_url_var], $this->_onclick);
            }
            
            return sprintf('<a href="%s"%s%s%s%s title="%s">%s</a>',
                           htmlentities($this->_url . $href, ENT_COMPAT, 'UTF-8'),
                           empty($this->_class_string) ? '' : ' '.$this->_class_string,
                           empty($this->_attributes)  ? '' : ' '.$this->_attributes,
                           empty($this->_accesskey)   ? '' : ' accesskey="'.$this->_link_data[$this->_url_var].'"',
                           empty($onclick)            ? '' : ' onclick="'.$onclick.'"',
                           $alt_text,
                           $link_text
            );
        } 
        elseif ($this->_http_method == 'POST') 
        {
            $href = $this->_url;
            if ( ! empty($_GET)) 
            {
                $href .= '?' . $this->_http_build_query_wrapper($_GET);
            }
            
            return sprintf("<a href='javascript:void(0)' onclick='%s'%s%s%s title='%s'>%s</a>",
                           $this->_generate_form_onclick($href, $this->_link_data),
                           empty($this->_class_string) ? '' : ' '.$this->_class_string,
                           empty($this->_attributes)  ? '' : ' '.$this->_attributes,
                           empty($this->_accesskey)   ? '' : ' accesskey=\''.$this->_link_data[$this->_url_var].'\'',
                           $alt_text,
                           $link_text
            );
        }
        return '';
    }

    // ------------------------------------------------------------------------
    
    /**
    * Mimics http_build_query() behavior in the way the data
    * in $data will appear when it makes it back to the server.
    *  For example:
    * $arr =  array('array' => array(array('hello', 'world'),
    *                                'things' => array('stuff', 'junk'));
    * http_build_query($arr)
    * and _generate_form_onclick('foo.php', $arr)
    * will yield
    * $_REQUEST['array'][0][0] === 'hello'
    * $_REQUEST['array'][0][1] === 'world'
    * $_REQUEST['array']['things'][0] === 'stuff'
    * $_REQUEST['array']['things'][1] === 'junk'
    *
    * However, instead of  generating a query string, it generates
    * Javascript to create and submit a form.
    *
    * @param   string  $formAction where the form should be submitted
    * @param   array   $data       the associative array of names and values
    * @return  string  A string of javascript that generates a form and submits it
    * @access  private
    */
    public function _generate_form_onclick($form_action, $data)
    {
        if ( ! is_array($data))   // Check we have an array to work with
        {
            throw new Exception('_generateForm() Parameter 1 expected to be Array or Object. Incorrect value given.');
            
            return FALSE;
        }

        if ( ! empty($this->_form_id)) 
        {
            $str = 'var form = document.getElementById("'.$this->_form_id.'"); var input = ""; ';
        } 
        else 
        {
            $str = 'var form = document.createElement("form"); var input = ""; ';
        }

        // We /shouldn't/ need to escape the URL ...
        $str .= sprintf('form.action = "%s"; ', htmlentities($form_action, ENT_COMPAT, 'UTF-8'));
        $str .= sprintf('form.method = "%s"; ', $this->_http_method);
        foreach ($data as $key => $val) 
        {
            $str .= $this->_generate_form_onclick_helper($val, $key);
        }

        if (empty($this->_form_id)) 
        {
            $str .= 'document.getElementsByTagName("body")[0].appendChild(form);';
        }

        $str .= 'form.submit(); return false;';
        return $str;
    }

    // ------------------------------------------------------------------------

    /**
    * This is used by _generate_form_onclick().
    * Recursively processes the arrays, objects, and literal values.
    *
    * @param mixed  $data Data that should be rendered
    * @param string $prev The name so far
    * @return string A string of Javascript that creates form inputs
    *                representing the data
    * @access private
    */
    public function _generate_form_onclick_helper($data, $prev = '')
    {
        $str = '';
        if (is_array($data) OR is_object($data)) 
        {
            // foreach key/visible member
            foreach ((array)$data as $key => $val) 
            {
                // append [$key] to prev
                $tempKey = sprintf('%s[%s]', $prev, $key);
                $str .= $this->_generate_form_onclick_helper($val, $tempKey);
            }
        } 
        else 
        {  
            // must be a literal value
            // escape newlines and carriage returns
            $search  = array("\n", "\r");
            $replace = array('\n', '\n');
            $escaped_data = str_replace($search, $replace, $data);
            
            // am I forgetting any dangerous whitespace?
            // would a regex be faster?
            // if it's already encoded, don't encode it again
            
            $hexchar = '&#[\dA-Fx]{2,};';
            if ( ! preg_match("/^(\s|($hexchar))*$/Uims", $escaped_data))  // Check if a string is an encoded multibyte string
            {
                $escaped_data = urlencode($escaped_data);
            }
            
            $escaped_data = htmlentities($escaped_data, ENT_QUOTES, 'UTF-8');

            $str .= 'input = document.createElement("input"); ';
            $str .= 'input.type = "hidden"; ';
            $str .= sprintf('input.name = "%s"; ', $prev);
            $str .= sprintf('input.value = "%s"; ', $escaped_data);
            $str .= 'form.appendChild(input); ';
        }
        return $str;
    }

    // ------------------------------------------------------------------------

    /**
    * Returns the correct link for the back/pages/next links
    *
    * @return array Data
    * @access private
    */
    public function _get_links_data()
    {
        $qs = array();
        if ($this->_import_query) 
        {
            if ($this->_http_method == 'POST') 
            {
                $qs = $_POST;
            } 
            elseif ($this->_http_method == 'GET') 
            {
                $qs = $_GET;
            }
        }
        
        foreach ($this->_exclude_vars as $exclude) 
        {
            $use_preg = preg_match('/^\/.*\/([Uims]+)?$/', $exclude);  // Returns true if the string is a regexp pattern
            
            foreach (array_keys($qs) as $qs_item) 
            {
                if ($use_preg) 
                {
                    if (preg_match($exclude, $qs_item, $matches)) 
                    {
                        foreach ($matches as $m) 
                        {
                            unset($qs[$m]);
                        }
                    }
                } 
                elseif ($qs_item == $exclude) 
                {
                    unset($qs[$qs_item]);
                    break;
                }
            }
        }
        
        
        if (count($this->_extra_vars)) 
        {
            $this->_recursive_urldecode($this->_extra_vars);
            $qs = array_merge($qs, $this->_extra_vars);
        }
        
        if (count($qs)
            && function_exists('get_magic_quotes_gpc')
            && -1 == version_compare(PHP_VERSION, '5.2.99')
            && get_magic_quotes_gpc()
        ) 
        {
            $this->_recursive_stripslashes($qs);
        }
        
        return $qs;
    }

    // ------------------------------------------------------------------------

    /**
    * Helper method
    *
    * @param string|array &$var variable to clean
    *
    * @return void
    * @access private
    */
    public function _recursive_stripslashes(&$var)
    {
        if (is_array($var)) 
        {
            foreach (array_keys($var) as $k) 
            {
                $this->_recursive_stripslashes($var[$k]);
            }
        } 
        else 
        {
            $var = stripslashes($var);
        }
    }

    // ------------------------------------------------------------------------

    /**
    * Helper method
    *
    * @param string|array &$var variable to decode
    *
    * @return void
    * @access private
    */
    public function _recursive_urldecode(&$var)
    {
        if (is_array($var)) 
        {
            foreach (array_keys($var) as $k) 
            {
                $this->_recursive_urldecode($var[$k]);
            }
            
        } 
        else 
        {
            $trans_tbl = array_flip(get_html_translation_table(HTML_ENTITIES));
            $var = strtr($var, $trans_tbl);
        }
    }

    // ------------------------------------------------------------------------

    /**
    * Returns back link
    *
    * @param string $url  URL to use in the link  [deprecated: use the factory instead]
    * @param string $link HTML to use as the link [deprecated: use the factory instead]
    *
    * @return string The link
    * @access private
    */
    public function _get_back_link($url = '', $link = '')
    {
        //legacy settings... the preferred way to set an option
        //now is passing it to the factory
        if ( ! empty($url)) 
        {
            $this->_base_url = $url;
        }
        if ( ! empty($link)) 
        {
            $this->_prev_img = $link;
        }
        
        $back = '';
        if ($this->_current_page > 1) 
        {
            $this->_link_data[$this->_url_var] = $this->get_prev_page();
            
            $back = $this->_render_link($this->_alt_prev, $this->_prev_img)
                  . $this->_spaces_before . $this->_spaces_after;
        } 
        else if ($this->_prev_img_empty !== null && $this->_total_pages > 1) 
        {
            $back = $this->_prev_img_empty
                  . $this->_spaces_before . $this->_spaces_after;
        }
        
        return $back;
    }

    // ------------------------------------------------------------------------
    
    /**
    * Returns next link
    *
    * @param string $url  URL to use in the link  [deprecated: use the factory instead]
    * @param string $link HTML to use as the link [deprecated: use the factory instead]
    *
    * @return string The link
    * @access private
    */
    public function _get_next_link($url = '', $link = '')
    {
        //legacy settings... the preferred way to set an option
        //now is passing it to the factory
        if ( ! empty($url)) 
        {
            $this->_base_url = $url;
        }
        
        if ( ! empty($link)) 
        {
            $this->_next_img = $link;
        }
        
        $next = '';
        if ($this->_current_page < $this->_total_pages) 
        {
            $this->_link_data[$this->_url_var] = $this->get_next_page();
            $next = $this->_spaces_after
                  . $this->_render_link($this->_alt_next, $this->_next_img)
                  . $this->_spaces_before . $this->_spaces_after;
        } 
        else if ($this->_next_img_empty !== NULL AND $this->_total_pages > 1) 
        {
            $next = $this->_spaces_after
                  . $this->_next_img_empty
                  . $this->_spaces_before . $this->_spaces_after;
        }
        
        return $next;
    }

    // ------------------------------------------------------------------------

    /**
    * Returns first link tag
    *
    * @param bool $raw should tag returned as array
    *
    * @return mixed string with html link tag or separated as array
    * @access private
    */
    public function _get_first_link_tag($raw = FALSE)
    {
        if ($this->is_first_page() OR ($this->_http_method != 'GET')) 
        {
            return $raw ? array() : '';
        }
        
        if ($raw) 
        {
            return array(
                'url'   => $this->_get_link_tag_url(1),
                'title' => $this->_first_link_title
            );
        }
        
        return sprintf('<link rel="first" href="%s" title="%s" />'."\n",
            $this->_get_link_tag_url(1),
            $this->_first_link_title
        );
    }

    // ------------------------------------------------------------------------

    /**
    * Returns previous link tag
    *
    * @param bool $raw should tag returned as array
    *
    * @return mixed string with html link tag or separated as array
    * @access private
    */
    public function _get_prev_link_tag($raw = false)
    {
        if ($this->is_first_page() OR ($this->_http_method != 'GET')) 
        {
            return $raw ? array() : '';
        }
        
        if ($raw) 
        {
            return array(
                'url'   => $this->_get_link_tag_url($this->get_prev_page()),
                'title' => $this->_prev_link_title
            );
        }
        
        return sprintf('<link rel="previous" href="%s" title="%s" />'."\n",
            $this->_get_link_tag_url($this->get_prev_page()),
            $this->_prev_link_title
        );
    }

    // ------------------------------------------------------------------------

    /**
    * Returns next link tag
    *
    * @param bool $raw should tag returned as array
    *
    * @return mixed string with html link tag or separated as array
    * @access private
    */
    public function _get_next_link_tag($raw = FALSE)
    {
        if ($this->is_last_page() OR ($this->_http_method != 'GET')) 
        {
            return $raw ? array() : '';
        }
        
        if ($raw) 
        {
            return array(
                'url'   => $this->_get_link_tag_url($this->get_next_page()),
                'title' => $this->_next_link_title
            );
        }
        
        return sprintf('<link rel="next" href="%s" title="%s" />'."\n",
            $this->_get_link_tag_url($this->get_next_page()),
            $this->_next_link_title
        );
    }

    // ------------------------------------------------------------------------

    /**
    * Returns last link tag
    *
    * @param bool $raw should tag returned as array
    *
    * @return mixed string with html link tag or separated as array
    * @access private
    */
    public function _get_last_link_tag($raw = false)
    {
        if ($this->is_last_page() OR ($this->_http_method != 'GET')) 
        {
            return $raw ? array() : '';
        }
        if ($raw) 
        {
            return array(
                'url'   => $this->_get_link_tag_url($this->_total_pages),
                'title' => $this->_last_link_title
            );
        }
        return sprintf('<link rel="last" href="%s" title="%s" />'."\n",
            $this->_get_link_tag_url($this->_total_pages),
            $this->_last_link_title
        );
    }

    // ------------------------------------------------------------------------

    /**
    * Helper method
    *
    * @param integer $page_id page id
    *
    * @return string the link tag url
    * @access private
    */
    public function _get_link_tag_url($page_id)
    {
        $this->_link_data[$this->_url_var] = $page_id;
    
        $href = '?' . $this->_http_build_query_wrapper($this->_link_data);
        
        return htmlentities($this->_url . $href, ENT_COMPAT, 'UTF-8');
    }

    // ------------------------------------------------------------------------

    /**
    * Returns a string with a XHTML SELECT menu,
    * useful for letting the user choose how many items per page should be
    * displayed. If parameter useSessions is TRUE, this value is stored in
    * a session var. The string isn't echoed right now so you can use it
    * with template engines.
    *
    * @param integer $start       starting value for the select menu
    * @param integer $end         ending value for the select menu
    * @param integer $step        step between values in the select menu
    * @param boolean $show_all_data If true, perPage is set equal to totalItems.
    * @param array   $extra_params (or string $optionText for BC reasons)
    *                - 'optionText': text to show in each option.
    *                  Use '%d' where you want to see the number of pages selected.
    *                - 'attributes': (html attributes) Tag attributes or
    *                  HTML attributes (id="foo" pairs), will be inserted in the
    *                  <select> tag
    *
    * @return string xhtml select box
    * @access public
    */
    public function get_per_page_select_box($start = 5, $end = 30, $step = 5, $show_all_data = FALSE, $extra_params = array())
    {
        loader::helper('core/driver');
                                            
        $widget = lib_driver($folder = 'pager', 'Pager_html_widgets', array('pager' => $this));
        
        return $widget->get_per_page_select_box($start, $end, $step, $show_all_data, $extra_params);
    }

    // ------------------------------------------------------------------------

    /**
    * Returns a string with a XHTML SELECT menu with the page numbers,
    * useful as an alternative to the links
    *
    * @param array  $params          - 'optionText': text to show in each option.
    *                                  Use '%d' where you want to see the number
    *                                  of pages selected.
    *                                - 'autoSubmit': if TRUE, add some js code
    *                                  to submit the form on the onChange event
    * @param string $extra_attributes (html attributes) Tag attributes or
    *                                HTML attributes (id="foo" pairs), will be
    *                                inserted in the <select> tag
    *
    * @return string xhtml select box
    * @access public
    */
    public function get_page_select_box($params = array(), $extra_attributes = '')
    {   
        loader::helper('core/driver');
                                            
        $widget = lib_driver($folder = 'pager', 'Pager_html_widgets', array('pager' => $this));
        
        return $widget->get_page_select_box($params, $extra_attributes);
    }

    // ------------------------------------------------------------------------

    /**
    * Print [1]
    *
    * @return string String with link to 1st page,
    *                or empty string if this is the 1st page.
    * @access private
    */
    public function _print_first_page()
    {
        if ($this->is_first_page()) 
        {
            return '';
        }
        
        $this->_link_data[$this->_url_var] = 1;
        
        return $this->_render_link(
                str_replace('%d', 1, $this->_alt_first),
                $this->_first_page_pre . $this->_first_page_text . $this->_first_page_post
        ) . $this->_spaces_before . $this->_spaces_after;
    }

    // ------------------------------------------------------------------------

    /**
    * Print [num_pages()]
    *
    * @return string String with link to last page,
    *                or empty string if this is the 1st page.
    * @access private
    */
    public function _print_last_page()
    {
        if ($this->is_last_page()) 
        {
            return '';
        }
        
        $this->_link_data[$this->_url_var] = $this->_total_pages;
        
        return $this->_render_link(
                str_replace('%d', $this->_total_pages, $this->_alt_last),
                $this->_last_page_pre . $this->_last_page_text . $this->_last_page_post
        );
    }

    // ------------------------------------------------------------------------

    /**
    * sets the private _first_page_text, _last_page_text variables
    * based on whether they were set in the options
    *
    * @return void
    * @access private
    */
    public function _set_first_last_text()
    {
        if ($this->_first_page_text == '') 
        {
            $this->_first_page_text = '1';
        }
        
        if ($this->_last_page_text == '') 
        {
            $this->_last_page_text = $this->_total_pages;
        }
    }

    // ------------------------------------------------------------------------

    /**
    * This is a slightly modified version of the http_build_query() function;
    * it heavily borrows code from PHP_Compat's http_build_query().
    * The main change is the usage of htmlentities instead of urlencode,
    * since it's too aggressive
    *
    * @param array $data array of querystring values
    *
    * @return string
    * @access private
    */
    public function _http_build_query_wrapper($data)
    {
        $data = (array)$data;
        if (empty($data)) 
        {
            return '';
        }
        
        $separator = ini_get('arg_separator.output');
        if ($separator == '&amp;') 
        {
            $separator = '&'; //the string is escaped by htmlentities anyway...
        }
        
        $tmp = array();
        foreach ($data as $key => $val) 
        {
            if (is_scalar($val)) 
            {
                
                $val = urlencode($val);  //array_push($tmp, $key.'='.$val);
                array_push($tmp, $key .'='. str_replace('%2F', '/', $val));
                continue;
            }
            
            if (is_array($val))  // If the value is an array, recursively parse it
            {
                array_push($tmp, $this->__http_build_query($val, urlencode($key)));
                continue;
            }
        }
        return implode($separator, $tmp);
    }

    // ------------------------------------------------------------------------

    /**
    * Helper function
    *
    * @param array  $array array of querystring values
    * @param string $name  key
    *
    * @return string
    * @access private
    */
    public function __http_build_query($array, $name)
    {
        $tmp = array();
        $separator = ini_get('arg_separator.output');
        if ($separator == '&amp;') 
        {
            $separator = '&'; //the string is escaped by htmlentities anyway...
        }
        
        foreach ($array as $key => $value) 
        {
            if (is_array($value)) 
            {
                //array_push($tmp, $this->__http_build_query($value, sprintf('%s[%s]', $name, $key)));
                array_push($tmp, $this->__http_build_query($value, $name.'%5B'.$key.'%5D'));
            } 
            elseif (is_scalar($value)) 
            {
                //array_push($tmp, sprintf('%s[%s]=%s', $name, htmlentities($key), htmlentities($value)));
                array_push($tmp, $name.'%5B'.urlencode($key).'%5D='.urlencode($value));
            } 
            elseif (is_object($value)) 
            {
                //array_push($tmp, $this->__http_build_query(get_object_vars($value), sprintf('%s[%s]', $name, $key)));
                array_push($tmp, $this->__http_build_query(get_object_vars($value), $name.'%5B'.$key.'%5D'));
            }
        }
        return implode($separator, $tmp);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Set and sanitize options
    *
    * @param mixed $options An associative array of option names and their values
    *
    * @return boolean TRUE if success
    * @access public
    */
    public function set_options($options)
    {
        foreach ($options as $key => $value) 
        {
            if (in_array($key, $this->_allowed_options) AND ( ! is_null($value))) 
            {
                $this->{'_' . $key} = $value;
            }
        }
        
        // autodetect http method
        if ( ! isset($options['httpMethod']) AND ! isset($_GET[$this->_url_var]) AND isset($_POST[$this->_url_var])) 
        {
            $this->_http_method = 'POST';
        } 
        else 
        {
            $this->_http_method = strtoupper($this->_http_method);
        }

        if (substr($this->_base_url, -1, 1) == '/') 
        {
            $this->_filename = ltrim($this->_filename, '/');  // strip leading slash
        }

        // removed _append
        if ($this->_fix_filename) 
        {
            $this->_filename = ''; // PAGER_CURRENT_FILENAME avoid possible user error;
        }
        
        $this->_url = $this->_base_url . $this->_filename;
        
        
        if (substr($this->_url, 0, 2) == '//') 
        {
            $this->_url = substr($this->_url, 1);
        }
        
        if (false === strpos($this->_alt_page, '%d')) 
        {
            //by default, append page number at the end
            $this->_alt_page .= ' %d';
        }

        $this->_class_string = '';
        if (strlen($this->_link_class)) 
        {
            $this->_class_string = 'class="'.$this->_link_class.'"';
        }

        if (strlen($this->_cur_page_link_class)) 
        {
            $this->_cur_page_span_pre  .= '<span class="'.$this->_cur_page_link_class.'">';
            $this->_cur_page_span_post = '</span>' . $this->_cur_page_span_post;
        }

        $this->_per_page = max($this->_per_page, 1); //avoid possible user errors

        if ($this->_use_sessions AND ! isset($_SESSION)) 
        {
            session_start();
        }
        
        if ( ! empty($_REQUEST[$this->_session_var])) 
        {
            $this->_per_page = max(1, (int)$_REQUEST[$this->_session_var]);
            if ($this->_use_sessions) 
            {
                $_SESSION[$this->_session_var] = $this->_per_page;
            }
        }

        if ( ! empty($_SESSION[$this->_session_var]) AND $this->_use_sessions) 
        {
             $this->_per_page = $_SESSION[$this->_session_var];
        }

        if ($this->_close_session) 
        {
            session_write_close();
        }

        $this->_spaces_before = str_repeat('&nbsp;', $this->_spaces_before_separator);
        $this->_spaces_after  = str_repeat('&nbsp;', $this->_spaces_after_separator);

        if (isset($_REQUEST[$this->_url_var]) AND empty($options['currentPage'])) 
        {
            $this->_current_page = (int)$_REQUEST[$this->_url_var];
        }
        
        $this->_current_page = max($this->_current_page, 1);
        $this->_link_data = $this->_get_links_data();

        return TRUE;
    }

}

// END Pager_common Class

/* End of file Pager_common.php */
/* Location: ./obullo/libraries/php5/pager/Pager_common.php */