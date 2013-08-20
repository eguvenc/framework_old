<?php
namespace Utf8\Src;

Class Ltrim {

    // ------------------------------------------------------------------------

    /**
     * UTF8 ltrim
     * Trim left space with utf8 support
     *
     * @access  public
     * @param string $str
     * @param string $charlist
     * @return string
     */
    function start($str, $charlist = null)
    {
        if ($charlist === null)
        {
            return ltrim($str);
        }

        $utf8 = new \Utf8(false);
        
        if ($utf8->isAscii($charlist))
        {
            return ltrim($str, $charlist);
        }

        $charlist = preg_replace('#[-\[\]:\\\\^/]#', '\\\\$0', $charlist);

        return preg_replace('/^['.$charlist.']+/u', '', $str);
    }
}

/* End of file ltrim.php */
/* Location: ./ob/utf8/releases/0.0.1/src/ltrim.php */