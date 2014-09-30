<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Convert PHP tags to entities
     *
     * @access   public
     * @param    string
     * @return   string
     */    
    function encodePhpTags($str)
    {
        return str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
    }

}