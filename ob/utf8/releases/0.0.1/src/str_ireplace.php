<?php
namespace Utf8\Src;

Class Str_Ireplace {

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
    public function start($search, $replace, $str, & $count = null)
    {
        $utf8 = new \Utf8(false);

        if($utf8->isAscii($search) AND $utf8->isAscii($replace) AND $utf8->isAscii($str))
        {
            return strIreplace($search, $replace, $str, $count);
        }

        if (is_array($str))
        {
            foreach ($str as $key => $val)
            {
                $str[$key] = $utf8->strIreplace($search, $replace, $val, $count);
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
                        $str = $utf8->strIreplace($search[$k], $replace[$k], $str, $count);
                    }
                    else
                    {
                        $str = $utf8->strIreplace($search[$k], '', $str, $count);
                    }
                }
                else
                {
                    $str = $utf8->strIreplace($search[$k], $replace, $str, $count);
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
/* Location: ./ob/utf8/releases/0.0.1/src/str_ireplace.php */