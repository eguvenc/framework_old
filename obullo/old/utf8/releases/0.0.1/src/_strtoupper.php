<?php
namespace Utf8\Src {

    // ------------------------------------------------------------------------

    /**
    * Makes a UTF-8 string uppercase. This is a UTF8-aware version
    * of [strtoupper](http://php.net/strtoupper).
    *
    * @param   string   mixed case string
    * @return  string
    */
    function _strtoupper($str)
    {
        global $config;

        return mb_strtoupper($str, $config['charset']);
    }

}

/* End of file _strtoupper.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_strtoupper.php */