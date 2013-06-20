<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 * @package         obullo    
 * @author          obullo.com
 * @since           Version 1.0.1
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * UTF8 str_ireplace
 *
 * @access  private
 * @param string $search
 * @param string $replace
 * @param string $str
 * @param int $count
 */

if( ! function_exists('utf8_str_ireplace'))
{
    function utf8_str_ireplace($search, $replace, $str, & $count = NULL)
    {
        $utf8 = lib('ob/utf8');

        if($utf8->is_ascii($search) AND $utf8->is_ascii($replace) AND $utf8->is_ascii($str))
        {
            return str_ireplace($search, $replace, $str, $count);
        }

        if (is_array($str))
        {
            foreach ($str as $key => $val)
            {
                $str[$key] = $utf8->str_ireplace($search, $replace, $val, $count);
            }

            return $str;
        }

        if (is_array($search))
        {
            $keys = array_keys($search);

            foreach ($keys as $k)
            {
                if (is_array($replace))
                {
                    if (array_key_exists($k, $replace))
                    {
                        $str = $utf8->str_ireplace($search[$k], $replace[$k], $str, $count);
                    }
                    else
                    {
                        $str = $utf8->str_ireplace($search[$k], '', $str, $count);
                    }
                }
                else
                {
                    $str = $utf8->str_ireplace($search[$k], $replace, $str, $count);
                }
            }

            return $str;
        }

        $search    = $utf8->strtolower($search);
        $str_lower = $utf8->strtolower($str);

        $total_matched_strlen = 0;
        $i = 0;

        while (preg_match('/(.*?)'.preg_quote($search, '/').'/s', $str_lower, $matches))
        {
            $matched_strlen = strlen($matches[0]);
            $str_lower = substr($str_lower, $matched_strlen);

            $offset = $total_matched_strlen + strlen($matches[1]) + ($i * (strlen($replace) - 1));
            $str = substr_replace($str, $replace, $offset, strlen($search));

            $total_matched_strlen += $matched_strlen;
            $i++;
        }

        $count += $i;

        return $str;
    }

}

/* End of file str_ireplace.php */
/* Location: ./obullo/helpers/drivers/utf8/str_ireplace.php */