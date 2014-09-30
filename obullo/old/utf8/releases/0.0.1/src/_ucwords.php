<?php
namespace Utf8\Src {

    // ------------------------------------------------------------------------

    /**
    * UTF8 ucwords
    *
    * @access  public
    * @param   string $str
    * @return  string
    */
    function _ucwords($str)
    {
        $utf8 = getInstance()->utf8;
        
        if($utf8->isAscii($str))
        {
            return ucwords($str);
        }

        // [\x0c\x09\x0b\x0a\x0d\x20] matches form feeds, horizontal tabs, vertical tabs, linefeeds and carriage returns.
        // This corresponds to the definition of a 'word' defined at http://php.net/ucwords
        return preg_replace(
            '/(?<=^|[\x0c\x09\x0b\x0a\x0d\x20])[^\x0c\x09\x0b\x0a\x0d\x20]/ue',
            '$utf8->_strtoupper(\'$0\')',
            $str
        );
    }

}

/* End of file _ucwords.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_ucwords.php */