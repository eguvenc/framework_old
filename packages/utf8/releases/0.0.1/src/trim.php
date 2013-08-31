<?php
namespace Utf8\Src;

Class Trim {

    // ------------------------------------------------------------------------

    /**
    * UTF8 trim
    *
    * @access  private
    * @param   string $str
    * @param   string $charlist
    * @return  string
    */
    function start($str, $charlist = null)
    {
        if ($charlist === null)
        {
            return trim($str);
        }

        $utf8 = new \Utf8(false);
        return $utf8->ltrim($utf8->rtrim($str, $charlist), $charlist);
    }
}

/* End of file trim.php */
/* Location: ./ob/utf8/releases/0.0.1/src/trim.php */