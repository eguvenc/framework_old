<?php
namespace Utf8\Src;

Class Strcasecmp {
    
    // ------------------------------------------------------------------------

    /**
     * UTF8 str_strcasecmp
     *
     * @access  private
     * @param   string $str1
     * @param   string $str2
     * @return string
     */
    public function start($str1, $str2)
    {
        $utf8 = new \Utf8();
        if ($utf8->isAscii($str1) AND $utf8->isAscii($str2))
        {
            return strcasecmp($str1, $str2);
        }

        $str1 = $utf8->strtolower($str1);
        $str2 = $utf8->strtolower($str2);
        
        return strcmp($str1, $str2);
    }

}
/* End of file str_casecmp.php */
/* Location: ./obullo/helpers/drivers/utf8/str_casecmp.php */