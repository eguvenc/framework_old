<?php
namespace Utf8\Src {
    
    // ------------------------------------------------------------------------
    
    /**
    * Returns the length of the given string. This is a UTF8-aware version
    * of [strlen](http://php.net/strlen).
    *
    *     $length = $this->utf8->_strlen($str);
    *
    * @param   string   string being measured for length
    * @return  integer
    */
    function _strlen($str)
    {
        global $config;

        return mb_strlen($str, $config['charset']);
    }

}

/* End of file _strlen.php */
/* Location: ./packages/utf8/releases/0.0.1/src/_strlen.php */