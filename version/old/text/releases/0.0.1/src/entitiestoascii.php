<?php
namespace Text\Src {

    // ------------------------------------------------------------------------

    /**
    * Entities to ASCII
    *
    * Converts character entities back to ASCII
    *
    * @access	public
    * @param	string
    * @param	bool
    * @return	string
    */
    function entitiesToAscii($str, $all = true)
    {
       if (preg_match_all('/\&#(\d+)\;/', $str, $matches))
       {
            for ($i = 0, $s = count($matches['0']); $i < $s; $i++)
            {				
                $digits = $matches['1'][$i];
                $out = '';

                if ($digits < 128)
                {
                    $out .= chr($digits);
                }
                elseif ($digits < 2048)
                {
                    $out .= chr(192 + (($digits - ($digits % 64)) / 64));
                    $out .= chr(128 + ($digits % 64));
                }
                else
                {
                    $out .= chr(224 + (($digits - ($digits % 4096)) / 4096));
                    $out .= chr(128 + ((($digits % 4096) - ($digits % 64)) / 64));
                    $out .= chr(128 + ($digits % 64));
                }

                $str = str_replace($matches['0'][$i], $out, $str);				
            }
       }

       if ($all)
       {
            $str = str_replace(array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;", "&#45;"),
                                                    array("&","<",">","\"", "'", "-"),
                                                    $str);
       }

       return $str;
    }

}