<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Error String
     *
     * Returns the error messages as a string, wrapped in the error delimiters
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   str
     */    
    function getErrorString($prefix = '', $suffix = '')
    {
        $form      = \Form::getConfig();
        $validator = getInstance()->validator;
        
        if (sizeof($validator->_error_array) === 0)         // No errrors, validation passes !
        {
            return '';
        }

        $str = '';        
        foreach ($validator->_error_array as $val)  // Generate the error string
        {
            if ($val != '')
            {
                if($prefix == '' AND $suffix == '')
                {
                    $str .= sprintf($form['notifications']['error'], $val);
                } 
                else 
                {
                    $str .= $prefix.$val.$suffix."\n";
                }
            }
        }
        
        return $str;
    }

}