<?php
namespace link {
    
    // ------------------------------------------------------------------------
    
    /**
    * Link Helper
    *
    * @package     Obullo
    * @subpackage  link
    * @category    url
    * @author      Obullo Team
    */
    Class start
    { 
        function __construct()
        {
            log\me('debug', 'Link Helper Initialized.');
        }
    }
    
    // ------------------------------------------------------------------------

    /**
    * Mailto Link
    *
    * @access	public
    * @param	string	the email address
    * @param	string	the link title
    * @param	mixed 	any attributes
    * @return	string
    */
    function mailto($email, $title = '', $attributes = '')
    {
        $title = (string) $title;

        if ($title == "")
        {
                $title = $email;
        }

        $attributes = _parseAttributes($attributes);

        return '<a href="mailto:'.$email.'"'.$attributes.'>'.$title.'</a>';
    }
    
    // ------------------------------------------------------------------------

    /**
    * Encoded Mailto Link
    *
    * Create a spam-protected mailto link written in Javascript
    *
    * @access	public
    * @param	string	the email address
    * @param	string	the link title
    * @param	mixed 	any attributes
    * @return	string
    */
    function safe_mailto($email, $title = '', $attributes = '')
    {
        $title = (string) $title;

        if ($title == "")
        {
                $title = $email;
        }

        for ($i = 0; $i < 16; $i++)
        {
                $x[] = substr('<a href="mailto:', $i, 1);
        }

        for ($i = 0; $i < strlen($email); $i++)
        {
                $x[] = "|" . ord(substr($email, $i, 1));
        }

        $x[] = '"';

        if ($attributes != '')
        {
                if (is_array($attributes))
                {
                        foreach ($attributes as $key => $val)
                        {
                                $x[] =  ' '.$key.'="';
                                for ($i = 0; $i < strlen($val); $i++)
                                {
                                        $x[] = "|" . ord(substr($val, $i, 1));
                                }
                                $x[] = '"';
                        }
                }
                else
                {
                        for ($i = 0; $i < strlen($attributes); $i++)
                        {
                                $x[] = substr($attributes, $i, 1);
                        }
                }
        }

        $x[] = '>';

        $temp = array();
        for ($i = 0; $i < strlen($title); $i++)
        {
                $ordinal = ord($title[$i]);

                if ($ordinal < 128)
                {
                        $x[] = "|".$ordinal;
                }
                else
                {
                        if (count($temp) == 0)
                        {
                                $count = ($ordinal < 224) ? 2 : 3;
                        }

                        $temp[] = $ordinal;
                        if (count($temp) == $count)
                        {
                                $number = ($count == 3) ? (($temp['0'] % 16) * 4096) + (($temp['1'] % 64) * 64) + ($temp['2'] % 64) : (($temp['0'] % 32) * 64) + ($temp['1'] % 64);
                                $x[] = "|".$number;
                                $count = 1;
                                $temp = array();
                        }
                }
        }

        $x[] = '<'; $x[] = '/'; $x[] = 'a'; $x[] = '>';

        $x_data = array_reverse($x);
        
        ob_start();
        ?>
        <script type="text/javascript" charset="utf-8"> 
        //<![CDATA[
        var l = new Array();
        <?php $i = 0; foreach ($x_data as $val) { ?>l[<?php echo $i++; ?>]='<?php echo $val; ?>';<?php } ?>

        for (var i = l.length-1; i >= 0; i = i-1)
        {
            if (l[i].substring(0, 1) == '|') { document.write("&#"+unescape(l[i].substring(1))+";"); }
            else { document.write(unescape(l[i])); }
        }
        //]]>
        </script>
       <?php
        return ob_get_clean();
    }

    // ------------------------------------------------------------------------

    /**
    * Auto-linker
    *
    * Automatically links URL and Email addresses.
    * Note: There's a bit of extra code here to deal with
    * URLs or emails that end in a period.  We'll strip these
    * off and add them after the link.
    *
    * @access	public
    * @param	string	the string
    * @param	string	the type: email, url, or both
    * @param	bool 	whether to create pop-up links
    * @return	string
    */
    function auto_link($str, $type = 'both', $popup = false)
    {
        if ($type != 'email')
        {
            if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches))
            {
                $pop = ($popup == true) ? " target=\"_blank\" " : "";

                for ($i = 0; $i < count($matches['0']); $i++)
                {
                    $period = '';
                    if (preg_match("|\.$|", $matches['6'][$i]))
                    {
                            $period = '.';
                            $matches['6'][$i] = substr($matches['6'][$i], 0, -1);
                    }

                    $str = str_replace($matches['0'][$i],
                                                            $matches['1'][$i].'<a href="http'.
                                                            $matches['4'][$i].'://'.
                                                            $matches['5'][$i].
                                                            $matches['6'][$i].'"'.$pop.'>http'.
                                                            $matches['4'][$i].'://'.
                                                            $matches['5'][$i].
                                                            $matches['6'][$i].'</a>'.
                                                            $period, $str);
                }
            }
        }

        if ($type != 'url')
        {
            if (preg_match_all("/([a-zA-Z0-9_\.\-\+]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)/i", $str, $matches))
            {
                for ($i = 0; $i < count($matches['0']); $i++)
                {
                    $period = '';
                    if (preg_match("|\.$|", $matches['3'][$i]))
                    {
                        $period = '.';
                        $matches['3'][$i] = substr($matches['3'][$i], 0, -1);
                    }

                    $str = str_replace($matches['0'][$i], safe_mailto($matches['1'][$i].'@'.$matches['2'][$i].'.'.$matches['3'][$i]).$period, $str);
                }
            }
        }

        return $str;
    }
    
}

/* End of file link.php */
/* Location: ./ob/link/releases/0.0.1/link.php */