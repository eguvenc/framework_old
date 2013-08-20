<?php
namespace xml {
    
    /**
    * Xml Helper
    *
    * @package     Obullo
    * @subpackage  Helpers
    * @category    xml
    * @link
    */
    Class start
    {
        function __construct()
        {
            \log\me('debug', 'Xml Helper Initialized.');
        }
    }

    // ------------------------------------------------------------------------

    /**
    * Convert array data to xml
    * 
    * @param array $data
    * @param string $xml
    * @return xml
    */
    function writer($data, $cdata = false, $encoding = 'UTF-8')
    { 
        if ( ! extension_loaded('libxml') )
        {
            throw new \Exception('PECL libxml extension not loaded on your server.');
        }

        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->startDocument('1.0', $encoding);
        $xml->startElement('root');

        _write_xml($xml, $data, $cdata);

        $xml->endElement();

        return $xml->outputMemory(true);
    }
    
    // ------------------------------------------------------------------------

    /**
     * Xml writer private function
     * 
     * @param XMLWriter $xml
     * @param array $data
     * @param boolen $cdata 
     */
    function _write_xml($xml, $data, $cdata)
    {
        foreach($data as $key => $value)
        {
            if(is_array($value))
            {
               $xml->startElement($key);
               
                _write_xml($xml, $value, $cdata);
                
               $xml->endElement();

               continue;
            }

            if($cdata) // full CDATA tags
            {
                $xml->startElement($key);
                $xml->writeCData($value);
                $xml->endElement();
            } 
            else
            {   
                $xml->writeElement($key, $value);
            }
        }
    }
    
    // ------------------------------------------------------------------------

    /**
    * Convert Reserved XML characters to Entities
    *
    * @access	public
    * @param	string
    * @return	string
    */
    function convert($str, $protect_all = false)
    {
        $temp = '__TEMP_AMPERSANDS__';

        // Replace entities to temporary markers so that 
        // ampersands won't get messed up    
        $str = preg_replace("/&#(\d+);/", "$temp\\1;", $str);

        if ($protect_all === true)
        {
            $str = preg_replace("/&(\w+);/",  "$temp\\1;", $str);
        }

        $str = str_replace(array("&","<",">","\"", "'", "-"),
                            array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;", "&#45;"),
                            $str);

        // Decode the temp markers back to entities        
        $str = preg_replace("/$temp(\d+);/","&#\\1;",$str);

        if ($protect_all === true)
        {
            $str = preg_replace("/$temp(\w+);/","&\\1;", $str);
        }

        return $str;
    }    
    
}

/* End of file xml.php */
/* Location: ./ob/xml/releases/0.0.1/xml.php */