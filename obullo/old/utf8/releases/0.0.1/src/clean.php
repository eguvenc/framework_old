<?php
namespace Utf8\Src {

    // ------------------------------------------------------------------------

    /**
    * Recursively cleans arrays, objects, and strings. Removes ASCII control
    * codes and converts to the requested charset while silently discarding
    * incompatible characters.
    *
    *     $utf8->utf8->clean($_GET); // Clean GET data
    *
    * [!!] This method requires [Iconv](http://php.net/iconv)
    *
    * @param   mixed   variable to clean
    * @param   string  character set, defaults to getConfig()['charset'];
    * @return  mixed
    * @uses    $this->utf8->stripAsciiCtrl
    * @uses    $this->utf8->isAscii
    */
    function clean($var, $charset = null)
    {
        global $config;

        $utf8 = getInstance()->utf8;

        if ( ! $charset)
        {
            $charset = $config['charset'];    // Use the application character set
        }

        if (is_array($var) OR is_object($var))
        {
            foreach ($var as $key => $val)
            {
                $var[$utf8->clean($key)] = $utf8->clean($val);
            }
        }
        elseif (is_string($var) AND $var !== '')
        {
            $var = $utf8->stripAsciiCtrl($var);  // Remove control characters

            if ( ! $utf8->isAscii($var))
            {
                $error_reporting = error_reporting(~E_NOTICE); // Disable notices
                
                $var = iconv($charset, $charset.'//IGNORE', $var); // iconv is expensive, so it is only used when needed

                error_reporting($error_reporting);  // Turn notices back on
            }
        }

        return $var;
    }

}

/* End of file clean.php */
/* Location: ./packages/utf8/releases/0.0.1/src/clean.php */