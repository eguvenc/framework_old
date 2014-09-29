<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Get Error Message
     *
     * Gets the error message associated with a particular field
     *
     * @access   public
     * @param    string    the field name
     * @return   void
     */    
    function getErrors($field = '', $prefix = '', $suffix = '')
    {    
        $validator = getInstance()->validator;

        if(empty($field))
        {
            return $validator->_error_array;
        }

        if ( ! isset($validator->_field_data[$field]['error']) OR $validator->_field_data[$field]['error'] == '')
        {
            return '';
        }

        if($prefix == '' AND $suffix == '')
        {
            return $validator->_field_data[$field]['error'];
        }

        return $prefix.$validator->_field_data[$field]['error'].$suffix;
    }

}