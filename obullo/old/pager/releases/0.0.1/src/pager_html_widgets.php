<?php

namespace Pager\Src;

/**
 * Pager Html Widgets
 *
 *
 * @package       packages
 * @subpackage    src
 * @category      pagination
 * @author        Derived from PEAR Pager package.
 * @see           Original package http://pear.php.net/package/Pager
 * @link          
 */
Class Pager_Html_Widgets
{
    public $pager = null;

    /**
     * Constructor
     *
     * @param object &$pager Pager instance
     */
    function __construct($options)
    {
        $this->pager = & $options['pager'];
        $this->sess  = new \Sess;
    }

    /**
     * Returns a string with a XHTML SELECT menu,
     * useful for letting the user choose how many items per page should be
     * displayed. If parameter useSessions is true, this value is stored in
     * a session var. The string isn't echoed right now so you can use it
     * with template engines.
     *
     * @param integer $start       starting value for the select menu
     * @param integer $end         ending value for the select menu
     * @param integer $step        step between values in the select menu
     * @param boolean $show_all_data If true, per_page is set equal to total_items.
     * @param array   $extra_params (or string $option_text for BC reasons)
     *                - 'option_text': text to show in each option.
     *                  Use '%d' where you want to see the number of pages selected.
     *                - 'attributes': (html attributes) Tag attributes or
     *                  HTML attributes (id="foo" pairs), will be inserted in the
     *                  <select> tag
     *                - 'check_max_limit': if true, Pager checks if $end is bigger
     *                  than $totalItems, and doesn't show the extra select options
     *                - 'auto_submit': if true, add some js code
     *                  to submit the form on the onChange event
     *
     * @return string xhtml select box
     * @access public
     */
    public function getPerPageSelectBox($start = 5, $end = 30, $step = 5, $show_all_data = false, $extra_params = array())
    {
        $option_text = '%d';  // FIXME: needs POST support
        $attributes = '';
        $check_max_limit = false;

        if (is_string($extra_params)) {
            $option_text = $extra_params;  // old behavior, BC maintained
        } else {
            if (array_key_exists('option_text', $extra_params)) {
                $option_text = $extra_params['option_text'];
            }
            if (array_key_exists('attributes', $extra_params)) {
                $attributes = $extra_params['attributes'];
            }
            if (array_key_exists('check_max_limit', $extra_params)) {
                $check_max_limit = $extra_params['check_max_limit'];
            }
        }

        if (!strstr($option_text, '%d')) {
            throw new \Exception('Page class invalid format - use "%d" as placeholder.');
        }

        $start = (int) $start;
        $end   = (int) $end;
        $step  = (int) $step;

        $session_var = $this->sess->get($this->pager->_session_var);

        if (!empty($session_var)) {
            $selected = (int) $session_var;
        } else {
            $selected = $this->pager->_per_page;
        }

        if ($check_max_limit AND $this->pager->_total_items >= 0 AND $this->pager->_total_items < $end) {
            $end = $this->pager->_total_items;
        }

        $tmp = '<select name="' . $this->pager->_session_var . '"';
        if (!empty($attributes)) {
            $tmp .= ' ' . $attributes;
        }

        if (!empty($extra_params['auto_submit'])) {
            if ('GET' == $this->pager->_http_method) {
                $selector = '\' + ' . 'this.options[this.selectedIndex].value + \'';

                ##########  ( Obullo Changes ..)  ##########  

                $tmp_link_data = $this->pager->_link_data;
                if (isset($tmp_link_data[$this->pager->_url_var])) {
                    $tmp_link_data[$this->pager->_url_var] = $this->pager->getCurrentPage();
                }

                $tmp_link_data[$this->pager->_session_var] = '1';
                $href = '?' . $this->pager->_httpBuildQueryWrapper($tmp_link_data);
                $href = htmlentities($this->pager->_url, ENT_COMPAT, 'UTF-8') . preg_replace(
                                '/(&|&amp;|\?)(' . $this->pager->_session_var . '=)(\d+)/', '\\1\\2' . $selector, htmlentities($href, ENT_COMPAT, 'UTF-8')
                );

                ##########  ( Obullo Changes ..)  ##########

                $tmp .= ' onchange="document.location.href=\''
                        . $href . '\''
                        . '"';
            } elseif ($this->pager->_http_method == 'POST') {
                $tmp .= " onchange='"
                        . $this->pager->_generateFormOnclick($this->pager->_url, $this->pager->_link_data)
                        . "'";
                $tmp = preg_replace(
                        '/(input\.name = \"' . $this->pager->_session_var . '\"; input\.value =) \"(\d+)\";/', '\\1 this.options[this.selectedIndex].value;', $tmp
                );
            }
        }

        $tmp .= '>';
        $last = $start;
        for ($i = $start; $i <= $end; $i+=$step) {
            $last = $i;
            $tmp .= '<option value="' . $i . '"';
            if ($i == $selected) {
                $tmp .= ' selected="selected"';
            }
            $tmp .= '>' . sprintf($option_text, $i) . '</option>';
        }

        if ($show_all_data AND $last != $this->pager->_total_items) {
            $tmp .= '<option value="' . $this->pager->_total_items . '"';
            if ($this->pager->_total_items == $selected) {
                $tmp .= ' selected="selected"';
            }
            $tmp .= '>';

            if (empty($this->pager->_show_all_text)) {
                $tmp .= str_replace('%d', $this->pager->_total_items, $option_text);
            } else {
                $tmp .= $this->pager->_show_all_text;
            }

            $tmp .= '</option>';
        }

        if (substr($tmp, -9, 9) !== '</option>') {
            //empty select
            $tmp .= '<option />';
        }
        $tmp .= '</select>';

        return $tmp;
    }

    /**
     * Returns a string with a XHTML SELECT menu with the page numbers,
     * useful as an alternative to the links
     *
     * @param array  $params          - 'option_text': text to show in each option.
     *                                  Use '%d' where you want to see the number
     *                                  of pages selected.
     *                                - 'auto_submit': if true, add some js code
     *                                  to submit the form on the onChange event
     * @param string $extraAttributes (html attributes) Tag attributes or
     *                                HTML attributes (id="foo" pairs), will be
     *                                inserted in the <select> tag
     *
     * @return string xhtml select box
     * @access public
     */
    public function getPageSelectBox($params = array(), $extra_attributes = '')
    {
        $option_text = '%d';
        if (array_key_exists('option_text', $params)) {
            $option_text = $params['option_text'];
        }

        if (!strstr($option_text, '%d')) {
            throw new \Exception('Invalid format - use "%d" as placeholder.');
        }

        $tmp = '<select name="' . $this->pager->_url_var . '"';

        if (!empty($extra_attributes)) {
            $tmp .= ' ' . $extra_attributes;
        }

        if (!empty($params['auto_submit'])) {
            if ($this->pager->_http_method == 'GET') {
                $selector = '\' + ' . 'this.options[this.selectedIndex].value + \'';

                // ( Obullo Changes ..)

                $href = '?' . $this->pager->_httpBuildQueryWrapper($this->pager->_link_data);
                $href = htmlentities($this->pager->_url, ENT_COMPAT, 'UTF-8') . preg_replace(
                                '/(&|&amp;|\?)(' . $this->pager->_url_var . '=)(\d+)/', '\\1\\2' . $selector, htmlentities($href, ENT_COMPAT, 'UTF-8')
                );

                // ( Obullo Changes ..)

                $tmp .= ' onchange="document.location.href=\''
                        . $href . '\''
                        . '"';
            } elseif ($this->pager->_http_method == 'POST') {
                $tmp .= " onchange='"
                        . $this->pager->_generateFormOnclick($this->pager->_url, $this->pager->_link_data)
                        . "'";
                $tmp = preg_replace(
                        '/(input\.name = \"' . $this->pager->_url_var . '\"; input\.value =) \"(\d+)\";/', '\\1 this.options[this.selectedIndex].value;', $tmp
                );
            }
        }
        $tmp .= '>';
        $start = 1;
        $end = $this->pager->numPages();
        $selected = $this->pager->getCurrentPage();

        for ($i = $start; $i <= $end; $i++) {
            $tmp .= '<option value="' . $i . '"';
            if ($i == $selected) {
                $tmp .= ' selected="selected"';
            }

            $tmp .= '>' . sprintf($option_text, $i) . '</option>';
        }

        $tmp .= '</select>';
        return $tmp;
    }

}

// END Pager_Html_Widgets Class

/* End of file Pager_Html_Widgets.php */
/* Location: ./packages/pager/releases/0.0.1/src/pager_html_widgets.php */