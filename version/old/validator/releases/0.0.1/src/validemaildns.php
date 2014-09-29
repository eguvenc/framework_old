<?php
namespace Validator\Src {
    
    // --------------------------------------------------------------------
    
    /**
     * Valid Email + DNS Check
     *
     * @access   public
     * @param    string
     * @return   bool
     */
    function validEmailDns($str)
    {
        $validator = getInstance()->validator;

        if($validator->validEmail($str) === true)
        {
            list($username,$domain) = explode('@',$str);
            
            if( ! checkdnsrr($domain,'MX'))
            {
                return false;
            }

            return true;
        }

        return false;
    }
    
}