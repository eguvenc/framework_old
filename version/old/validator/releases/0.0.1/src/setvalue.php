<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Get the value from a form
     *
     * Permits you to repopulate a form field with the value it was submitted
     * with, or, if that value doesn't exist, with the default
     *
     * @access   public
     * @param    string    the field name
     * @param    string
     * @return   void
     */    
    function setValue($field = '', $default = '')
    {
        $validator = getInstance()->validator;

        if ( ! isset($validator->_field_data[$field]))
        {
            return $default;
        }
        
        if(isset($validator->_field_data[$field]['postdata']))
        { 
            return $validator->_field_data[$field]['postdata'];
        } 
        elseif(isset($_REQUEST[$field]))
        {
            return $_REQUEST[$field];
        }
        
        return;
    }

}