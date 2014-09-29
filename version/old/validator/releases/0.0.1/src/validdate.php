<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
     /**
     * Validate Date
     *
     * @access   public
     * @param    string date
     * @param    string date_format
     * @return   string
     */
    function validDate($date, $format = 'mm-dd-yyyy')
    {        
        $format = strtoupper(trim($format));
        
        if(strpos($date, ' ') > 0 OR strlen($date) > 10 OR strlen($date) < 10) // well format and no time
        {
            return false;
        }
        
        // get the date symbols.
        $date_array   = preg_split('/[\w]+/', $date, -1, PREG_SPLIT_NO_EMPTY);
        $format_array = preg_split('/[\w]+/', $format, -1, PREG_SPLIT_NO_EMPTY);

        if( ! isset($date_array[0]) AND ! isset($format_array[0]))
        {
            return false;
        }
        
        $format = str_replace($format_array[0], '.', $format);

        switch ($format)   // Validate the right date format.
        {
            case 'YYYY.MM.DD':

                list($y, $m, $d) = explode($date_array[0], $date);     

                break;

            case 'DD.MM.YYYY':

                list($d, $m, $y) = explode($date_array[0], $date);   

                break;

            case 'MM.DD.YYYY':

                list($m, $d, $y) = explode($date_array[0], $date);  

                break;
        }


        return checkdate($m, $d, sprintf('%04u', $y));
    }
    
}