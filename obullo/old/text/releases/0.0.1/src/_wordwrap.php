<?php
namespace Text\Src {

    // ------------------------------------------------------------------------

    /**
    * Word Wrap
    *
    * Wraps text at the specified character.  Maintains the integrity of words.
    * Anything placed between {unwrap}{/unwrap} will not be word wrapped, nor
    * will URLs.
    *
    * @access	public
    * @param	string	the text string
    * @param	integer	the number of characters to wrap at
    * @return	string
    */
    function _wordWrap($str, $charlim = '76')
    {
        // Se the character limit
        if ( ! is_numeric($charlim))
        {
            $charlim = 76;   
        }

        // Reduce multiple spaces
        $str = preg_replace("| +|", " ", $str);

        // Standardize newlines
        if (strpos($str, "\r") !== false)
        {
            $str = str_replace(array("\r\n", "\r"), "\n", $str);			
        }

        // If the current word is surrounded by {unwrap} tags we'll 
        // strip the entire chunk and replace it with a marker.
        $unwrap = array();
        if (preg_match_all("|(\{unwrap\}.+?\{/unwrap\})|s", $str, $matches))
        {
            for ($i = 0; $i < count($matches['0']); $i++)
            {
                $unwrap[] = $matches['1'][$i];				
                $str = str_replace($matches['1'][$i], "{{unwrapped".$i."}}", $str);
            }
        }

        // Use PHP's native function to do the initial wordwrap.  
        // We set the cut flag to false so that any individual words that are 
        // too long get left alone.  In the next step we'll deal with them.
        $str = wordwrap($str, $charlim, "\n", false);

        // Split the string into individual lines of text and cycle through them
        $output = "";
        foreach (explode("\n", $str) as $line) 
        {
            // Is the line within the allowed character count?
            // If so we'll join it to the output and continue
            if (strlen($line) <= $charlim)
            {
                $output .= $line."\n";			
                continue;
            }

            $temp = '';
            while((strlen($line)) > $charlim) 
            {
                // If the over-length word is a URL we won't wrap it
                if (preg_match("!\[url.+\]|://|wwww.!", $line))
                {
                    break;
                }

                // Trim the word down
                $temp .= substr($line, 0, $charlim-1);
                $line = substr($line, $charlim-1);
            }

            // If $temp contains data it means we had to split up an over-length 
            // word into smaller chunks so we'll add it back to our current line
            if ($temp != '')
            {
                $output .= $temp . "\n" . $line; 
            }
            else
            {
                $output .= $line;
            }

            $output .= "\n";
        }

        // Put our markers back
        if (count($unwrap) > 0)
        {	
            foreach ($unwrap as $key => $val)
            {
                $output = str_replace("{{unwrapped".$key."}}", $val, $output);
            }
        }

        // Remove the unwrap tags
        $output = str_replace(array('{unwrap}', '{/unwrap}'), '', $output);
        
        return $output;	
    }
    
}