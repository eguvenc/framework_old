<?php
namespace Utf8\Src {

    // ------------------------------------------------------------------------
    
    /**
     * Replaces special/accented UTF-8 characters by ASCII-7 "equivalents".
     *
     *     $ascii = $this->utf8->toAscii($utf8);
     *
     * @param   string   string to transliterate
     * @param   integer  -1 lowercase only, +1 uppercase only, 0 both cases
     * @return  string
     */

    /**
    * UTF8 transliterate_to_ascii
    *
    * @access  public
    * @param   string $str
    * @param   int $case
    * @return  string
    */
    function toAscii($str, $locale = 'en_US', $direction = 'general')
    {
        return getInstance()->utf8->transliterateAll($str, $direction, $locale);
    }
    
}

/* End of file toascii.php */
/* Location: ./packages/utf8/releases/0.0.1/src/toascii.php */
