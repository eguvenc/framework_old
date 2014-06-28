<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Set The Error Delimiter
     *
     * Permits a prefix/suffix to be added to each error message
     *
     * @access   public
     * @param    string
     * @param    string
     * @return   void
     */    
    function setErrorDelimiters($prefix = '<p>', $suffix = '</p>')
    {
        $validator = getInstance()->validator;

        $validator->_error_prefix = $prefix;
        $validator->_error_suffix = $suffix;
    }
    
}