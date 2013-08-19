<?php
namespace Pager\Src;

/**
 * Pager Sliding Driver
 *
 *
 * @package       Obullo
 * @subpackage    src.pager_sliding
 * @category      pagination
 * @author        Obullo Team
 * @author        Derived from PEAR Pager package.
 * @see           Original package http://pear.php.net/package/Pager
 * @link          
 */

Class Pager_Sliding extends Pager_Common
{

    /**
    * Constructor
    *
    * @param array $options Associative array of option names and their values
    *
    * @access public
    */
    function __construct($options = array())
    {
        //set default Pager_Sliding options
        $this->_delta                   = 2;
        $this->_prev_img                = '&laquo;';
        $this->_next_img                = '&raquo;';
        $this->_separator               = '|';
        $this->_spaces_before_separator = 3;
        $this->_spaces_after_separator  = 3;
        $this->_cur_page_span_pre       = '<b>';
        $this->_cur_page_span_post      = '</b>';
        
        //set custom options
        $res = $this->setOptions($options);
        
        if ($res !== true) 
        {
            throw new \Exception('Pager Unknown Error.');
        }
        
        $this->build();
    }

    // ------------------------------------------------------------------------

    /**
    * "Overload" PEAR::Pager method. VOID. Not needed here...
    *
    * @param integer $index Offset to get page_id for
    *
    * @return void
    * @deprecated
    * @access public
    */
    public function getPageByOffset($index) {}

    // ------------------------------------------------------------------------

    /**
    * Given a page_id, it returns the limits of the range of pages displayed.
    * While getOffsetByPage() returns the offset of the data within the
    * current page, this method returns the offsets of the page numbers interval.
    * E.g., if you have page_id=5 and delta=2, it will return (3, 7).
    * page_id of 9 would give you (4, 8).
    * If the method is called without parameter, page_id is set to currentPage#.
    *
    * @param integer $page_id page_id to get offsets for
    *
    * @return array  First and last offsets
    * @access public
    */
    public function getPageRangeByPage($page_id = null)
    {
        $page_id = isset($page_id) ? (int)$page_id : $this->_current_page;
        
        if ( ! isset($this->_page_data)) 
        {
            $this->_generatePageData();
        }
        
        if (isset($this->_page_data[$page_id]) OR is_null($this->_item_data)) 
        {
            if ($this->_expanded) 
            {
                $min_surplus = ($page_id <= $this->_delta) ? ($this->_delta - $page_id + 1) : 0;
                $max_surplus = ($page_id >= ($this->_total_pages - $this->_delta)) ?
                                ($page_id - ($this->_total_pages - $this->_delta)) : 0;
            } 
            else 
            {
                $min_surplus = $max_surplus = 0;
            }
            
            return array(
                max($page_id - $this->_delta - $max_surplus, 1),
                min($page_id + $this->_delta + $min_surplus, $this->_total_pages)
            );
        }
        return array(0, 0);
    }

    // ------------------------------------------------------------------------
    
    /**
    * Returns back/next/first/last and page links,
    * both as ordered and associative array.
    *
    * @param integer $page_id Optional page_id. If specified, links for that page
    *                        are provided instead of current one.
    * @param string  $dummy  used to comply with parent signature (leave empty)
    *
    * @return array back/pages/next/first/last/all links
    * @access public
    */
    public function getLinks($page_id = null, $dummy = '')
    {
        if ( ! is_null($page_id)) 
        {
            $_sav = $this->_current_page;
            $this->_current_page = $page_id;

            $this->links = '';
            if ($this->_total_pages > (2 * $this->_delta + 1)) 
            {
                $this->links .= $this->_printFirstPage();
            }
            
            $this->links .= $this->_getBackLink();
            $this->links .= $this->_getPageLinks();
            $this->links .= $this->_getNextLink();
            
            if ($this->_total_pages > (2 * $this->_delta + 1)) 
            {
                $this->links .= $this->_printLastPage();
            }
        }

        $back        = str_replace('&nbsp;', '', $this->_getBackLink());
        $next        = str_replace('&nbsp;', '', $this->_getNextLink());
        $pages       = $this->_getPageLinks();
        $first       = $this->_printFirstPage();
        $last        = $this->_printLastPage();
        $all         = $this->links;
        $link_tags   = $this->link_tags;
        $link_tags_raw = $this->link_tags_raw;

        if ( ! is_null($page_id)) 
        {
            $this->_current_page = $_sav;
        }

        return array(
            $back,
            $pages,
            trim($next),
            $first,
            $last,
            $all,
            $link_tags,
            'back'        => $back,
            'pages'       => $pages,
            'next'        => $next,
            'first'       => $first,
            'last'        => $last,
            'all'         => $all,
            'link_tags'   => $link_tags,
            'link_tags_raw' => $link_tags_raw,
        );
    }

    // ------------------------------------------------------------------------
    
    /**
    * Returns pages link
    *
    * @param string $url URL string [deprecated]
    *
    * @return string Links
    * @access private
    */
    public function _getPageLinks($url = '')
    {
        //legacy setting... the preferred way to set an option now
        //is adding it to the constuctor
        if ( ! empty($url)) 
        {
            $this->_base_url = $url;
        }
        
        //If there's only one page, don't display links
        if ($this->_clear_if_void AND ($this->_total_pages < 2)) 
        {
            return '';
        }

        $links = '';
        if ($this->_total_pages > (2 * $this->_delta + 1)) 
        {
            if ($this->_expanded) 
            {
                if (($this->_total_pages - $this->_delta) <= $this->_current_page) 
                {
                    $expansion_before = $this->_current_page - ($this->_total_pages - $this->_delta);
                } 
                else 
                {
                    $expansion_before = 0;
                }
                
                for ($i = $this->_current_page - $this->_delta - $expansion_before; $expansion_before; $expansion_before--, $i++) 
                {
                    $print_separator_flag = ($i != $this->_current_page + $this->_delta); // && ($i != $this->_total_pages - 1)
                    
                    $this->range[$i] = false;
                    
                    $this->_link_data[$this->_url_var] = $i;
                    $links .= $this->_renderLink(str_replace('%d', $i, $this->_alt_page), $i)
                           . $this->_spaces_before
                           . ($print_separator_flag ? $this->_separator.$this->_spaces_after : '');
                }
            }

            $expansion_after = 0;
            for ($i = $this->_current_page - $this->_delta; ($i <= $this->_current_page + $this->_delta) && ($i <= $this->_total_pages); $i++) 
            {
                if ($i < 1) 
                {
                    ++$expansion_after;
                    continue;
                }

                // check when to print separator
                $print_separator_flag = (($i != $this->_current_page + $this->_delta) && ($i != $this->_total_pages));

                if ($i == $this->_current_page) 
                {
                    $this->range[$i] = true;
                    $links .= $this->_cur_page_span_pre . $i . $this->_cur_page_span_post;
                } 
                else 
                {
                    $this->range[$i] = false;
                    $this->_link_data[$this->_url_var] = $i;
                    $links .= $this->_renderLink(str_replace('%d', $i, $this->_alt_page), $i);
                }
                
                $links .= $this->_spaces_before
                        . ($print_separator_flag ? $this->_separator.$this->_spaces_after : '');
            }

            if ($this->_expanded AND $expansion_after) 
            {
                $links .= $this->_separator . $this->_spaces_after;
                for ($i = $this->_current_page + $this->_delta +1; $expansion_after; $expansion_after--, $i++) 
                {
                    $print_separator_flag = ($expansion_after != 1);
                    $this->range[$i] = false;
                    
                    $this->_link_data[$this->_url_var] = $i;
                    $links .= $this->_renderLink(str_replace('%d', $i, $this->_alt_page), $i)
                      . $this->_spaces_before
                      . ($print_separator_flag ? $this->_separator.$this->_spaces_after : '');
                }
            }

        } 
        else 
        {
            // if $this->_total_pages <= (2*Delta+1) show them all
            for ($i=1; $i<=$this->_total_pages; $i++) 
            {
                if ($i != $this->_current_page) 
                {
                    $this->range[$i] = false;
                    
                    $this->_link_data[$this->_url_var] = $i;
                    $links .= $this->_renderLink(str_replace('%d', $i, $this->_alt_page), $i);
                } 
                else 
                {
                    $this->range[$i] = true;
                    $links .= $this->_cur_page_span_pre . $i . $this->_cur_page_span_post;
                }
                
                $links .= $this->_spaces_before
                       . (($i != $this->_total_pages) ? $this->_separator.$this->_spaces_after : '');
            }
        }
        return $links;
    }

}

// END Pager_sliding Class

/* End of file Pager_sliding.php */
/* Location: ./ob/pager/releases/0.0.1/src/pager_sliding.php */