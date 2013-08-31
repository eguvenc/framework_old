<?php
namespace Utf8\Src;

Class Ucfirst {

    // ------------------------------------------------------------------------

    /**
    * UTF8 ucfirst
    *
    * @access  private
    * @param   string $str
    * @return  string
    */
    public function start($str)
    {
        $utf8 = new \Utf8(false);

        if($utf8->isAscii($str))
        {
            return ucfirst($str);
        }

        preg_match('/^(.?)(.*)$/us', $str, $matches);
        
	return $utf8->strtoupper($matches[1]).$matches[2];
    }

}

/* End of file ucfirst.php */
/* Location: ./ob/utf8/releases/0.0.1/src/ucfirst.php */