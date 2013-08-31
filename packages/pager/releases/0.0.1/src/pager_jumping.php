<?php
namespace Pager\Src;

/**
 * Pager Jumping Driver
 *
 *
 * @package       packages
 * @subpackage    src
 * @category      pagination
 * @author        Derived from PEAR Pager package.
 * @see           Original package http://pear.php.net/package/Pager   
 */

Class Pager_Jumping extends Pager_Common
{
    /**
    * Constructor
    *
    * @param array $options Associative array of option 
    *                       names and their values
    * @access public
    */
    function __construct($options = array())
    {
        $res = $this->setOptions($options);
        
        if ($res !== true) 
        {
            throw new \Exception('Pager Unknown Error.');
        }
    
        $this->build();
    }
    
    // ------------------------------------------------------------------------

    /**
    * Returns page_id for given offset
    *
    * @param integer $index Offset to get page_id for
    *
    * @return int page_id for given offset
    */
    public function getPageByOffset($index)
    {
        if (!isset($this->_page_data)) 
        {
            $this->_generatePageData();
        }

        if (($index % $this->_per_page) > 0) 
        {
            $page_id = ceil((float)$index / (float)$this->_per_page);
        } 
        else 
        {
            $page_id = $index / $this->_per_page;
        }
        
        return $page_id;
    }

    // ------------------------------------------------------------------------
    
    /**
    * Given a page_id, it returns the limits of the range of pages displayed.
    * While getOffsetByPage() returns the offset of the data within the
    * current page, this method returns the offsets of the page numbers interval.
    * E.g., if you have page_id=3 and delta=10, it will return (1, 10).
    * page_id of 8 would give you (1, 10) as well, because 1 <= 8 <= 10.
    * page_id of 11 would give you (11, 20).
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
        
        if (isset($this->_page_data[$page_id]) || is_null($this->_item_data)) 
        {
            // I'm sure I'm missing something here, but this formula works
            // so I'm using it until I find something simpler.
            $start = ((($page_id + (($this->_delta - ($page_id % $this->_delta))) % $this->_delta) / $this->_delta) - 1) * $this->_delta +1;
            
            return array(
                max($start, 1),
                min($start+$this->_delta-1, $this->_total_pages)
            );
            
        } 
        else 
        {
            return array(0, 0);
        }
    }

    // ------------------------------------------------------------------------

    /**
    * Returns back/next/first/last and page links,
    * both as ordered and associative array.
    *
    * NB: in original PEAR::Pager this method accepted two parameters,
    * $back_html and $next_html. Now the only parameter accepted is
    * an integer ($page_id), since the html text for prev/next links can
    * be set in the constructor. If a second parameter is provided, then
    * the method act as it previously did. This hack's only purpose is to
    * mantain backward compatibility.
    *
    * @param integer $page_id    Optional page_id. If specified, links for that 
    *                           page are provided instead of current one.
    *                           [ADDED IN NEW PAGER VERSION]
    * @param string  $next_html HTML to put inside the next link
    *                           [deprecated: use the factory instead]
    *
    * @return array Back/pages/next links
    */
    public function getLinks($page_id = null, $next_html = '')
    {
        //BC hack
        if ( ! empty($next_html)) 
        {
            $back_html = $page_id;
            $page_id    = null;
        } 
        else 
        {
            $back_html = '';
        }

        if ( ! is_null($page_id)) 
        {
            $this->links = '';
            if ($this->_total_pages > $this->_delta) 
            {
                $this->links .= $this->_printFirstPage();
            }

            $_sav = $this->_current_page;
            $this->_current_page = $page_id;

            $this->links .= $this->_getBackLink('', $back_html);
            $this->links .= $this->_getPageLinks();
            $this->links .= $this->_getNextLink('', $next_html);
            
            if ($this->_total_pages > $this->_delta) 
            {
                $this->links .= $this->_printLastPage();
            }
        }

        $back          = str_replace('&nbsp;', '', $this->_getBackLink());
        $next          = str_replace('&nbsp;', '', $this->_getNextLink());
        $pages         = $this->_getPageLinks();
        $first         = $this->_printFirstPage();
        $last          = $this->_printLastPage();
        $all           = $this->links;
        $link_tags     = $this->link_tags;
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
    * @param string $url URL to use in the link
    *                    [deprecated: use the constructor instead]
    *
    * @return string Links
    * @access private
    */
    public function _getPageLinks($url = '')
    {
        // legacy setting... the preferred way to set an option now
        // is adding it to the constuctor
        if ( ! empty($url)) 
        {
            $this->_base_url = $url;
        }

        //If there's only one page, don't display links
        if ($this->_clear_if_void AND ($this->_total_pages < 2)) 
        {
            return '';
        }

        $links  = '';
        $limits = $this->getPageRangeByPage($this->_current_page);

        for ($i=$limits[0]; $i<=min($limits[1], $this->_total_pages); $i++) 
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
        return $links;
    }

}

// END Pager_Jumping Class

/* End of file Pager_Jumping.php */
/* Location: ./packages/pager/releases/0.0.1/src/pager_jumping.php */