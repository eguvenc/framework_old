<?php
namespace Utf8\Src {

    // ------------------------------------------------------------------------
    
    /**
    * Returns a string or an array with all occurrences of search in subject
    * (ignoring case) and replaced with the given replace value. This is a
    * UTF8-aware version of [str_ireplace](http://php.net/str_ireplace).
    *
    * [!!] This function is very slow compared to the native version. Avoid
    * using it when possible.
    *
    * @param   string|array  text to replace
    * @param   string|array  replacement text
    * @param   string|array  subject text
    * @param   integer       number of matched and replaced needles will be returned via this parameter which is passed by reference
    * @return  string        if the input was a string
    * @return  array         if the input was an array
    */

    /**
     * UTF8 str_ireplace
     *
     * @access  public
     * @param string $search
     * @param string $replace
     * @param string $str
     * @param int $count
     */
    function _str_ireplace($search, $replace, $str, & $count = null)
    {
        $utf8 = getInstance()->utf8;

        if($utf8->isAscii($search) AND $utf8->isAscii($replace) AND $utf8->isAscii($str))
        {
            return str_ireplace($search, $replace, $str, $count);
        }

        if (is_array($str))
        {
            foreach ($str as $key => $val)
            {
                $str[$key] = $utf8->_str_ireplace($search, $replace, $val, $count);
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
                        $str = $utf8->_str_ireplace($search[$k], $replace[$k], $str, $count);
                    }
                    else
                    {
                        $str = $utf8->_str_ireplace($search[$k], '', $str, $count);
                    }
                }
                else
                {
                    $str = $utf8->_str_ireplace($search[$k], $replace, $str, $count);
                }
            }

            return $str;
        }

        $search    = $utf8->_strtolower($search);
        $str_lower = $utf8->_strtolower($str);

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

/* End of file _str_ireplace.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_str_ireplace.php */