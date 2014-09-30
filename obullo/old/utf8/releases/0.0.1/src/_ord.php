<?php
namespace Utf8\Src {

    // ------------------------------------------------------------------------
    
    /**
    * Returns the unicode ordinal for a character. This is a UTF8-aware
    * version of [ord](http://php.net/ord).
    *
    *     $digit = $this->utf8->_ord($character);
    *
    * @param   string   UTF-8 encoded character
    * @return  integer
    */

    /**
     * UTF8 ord
     *
     * @access  public
     * @param string $str
     */
    function _ord($chr)
    {
        global $logger;

        $ord0 = ord($chr);

        if ($ord0 >= 0 AND $ord0 <= 127)
        {
            return $ord0;
        }

        if ( ! isset($chr[1]))
        {
            $logger->debug('Utf8->_ord: Short sequence - at least 2 bytes expected, only 1 seen in this char: '. $chr);
            
            return false;
        }

        $ord1 = ord($chr[1]);

        if ($ord0 >= 192 AND $ord0 <= 223)
        {
            return ($ord0 - 192) * 64 + ($ord1 - 128);
        }

        if ( ! isset($chr[2]))
        {
             $logger->debug('Utf8->_ord: Short sequence - at least 3 bytes expected, only 2 seen in this char: '. $chr);

            return false;
        }

        $ord2 = ord($chr[2]);

        if ($ord0 >= 224 AND $ord0 <= 239)
        {
            return ($ord0 - 224) * 4096 + ($ord1 - 128) * 64 + ($ord2 - 128);
        }    

        if ( ! isset($chr[3]))
        {
            $logger->debug('Utf8->_ord: Short sequence - at least 4 bytes expected, only 3 seen in this char: '. $chr);

            return false;
        }

        $ord3 = ord($chr[3]);

        if ($ord0 >= 240 AND $ord0 <= 247)
        {
            return ($ord0 - 240) * 262144 + ($ord1 - 128) * 4096 + ($ord2-128) * 64 + ($ord3 - 128);
        }

        if ( ! isset($chr[4]))
        {
            $logger->debug('Utf8->_ord: Short sequence - at least 5 bytes expected, only 4 seen in this char: '. $chr);

            return false;
        }

        $ord4 = ord($chr[4]);

        if ($ord0 >= 248 AND $ord0 <= 251)
        {
            return ($ord0 - 248) * 16777216 + ($ord1-128) * 262144 + ($ord2 - 128) * 4096 + ($ord3 - 128) * 64 + ($ord4 - 128);
        }

        if ( ! isset($chr[5]))
        {
            $logger->debug('Utf8->_ord: Short sequence - at least 6 bytes expected, only 5 seen in this char: '. $chr);

            return false;
        }

        if ($ord0 >= 252 AND $ord0 <= 253)
        {
            return ($ord0 - 252) * 1073741824 + ($ord1 - 128) * 16777216 + ($ord2 - 128) * 262144 + ($ord3 - 128) * 4096 + ($ord4 - 128) * 64 + (ord($chr[5]) - 128);
        }            

        if ($ord0 >= 254 AND $ord0 <= 255)
        {
            $logger->debug('Utf8->_ord: Invalid surrogate ordinal '.$ord0 . 'in this char: '. $chr);

            return false;
        }
    }

}
    
/* End of file _ord.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_ord.php */