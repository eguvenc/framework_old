<?php
namespace Text\Src {

    // ------------------------------------------------------------------------

    /**
    * High ASCII to Entities
    *
    * Converts High ascii text and MS Word special characters to character entities
    *
    * @access	public
    * @param	string
    * @return	string
    */
    function asciiToEntities($str)
    {
        $count	= 1;
        $out	= '';
        $temp	= array();

        for ($i = 0, $s = strlen($str); $i < $s; $i++)
        {
            $ordinal = ord($str[$i]);

            if ($ordinal < 128)
            {
                /*
                        If the $temp array has a value but we have moved on, then it seems only
                        fair that we output that entity and restart $temp before continuing. -Paul
                */
                if (count($temp) == 1)
                {
                    $out  .= '&#'.array_shift($temp).';';
                    $count = 1;
                }

                $out .= $str[$i];
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

                    $out .= '&#'.$number.';';
                    $count = 1;
                    $temp = array();
                }
            }
        }

        return $out;
    }
    
}