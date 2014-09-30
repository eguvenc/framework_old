<?php
namespace Validator\Src {

    // --------------------------------------------------------------------

    /**
     * Valid Emails
     *
     * @access   public
     * @param    string
     * @return   bool
     */    
    function validEmails($str, $dns_check = '')
    {
        $validator = getInstance()->validator;

        $dns_check = strtoupper($dns_check);
        
        $dns = '';
        if($dns_check == 'true' OR $dns_check == 1)
        {
            $dns = 'Dns';
        }
        
        if (strpos($str, ',') === false)
        {
            return $validator->{'validEmail'.$dns}(trim($str));
        }
        
        foreach(explode(',', $str) as $email)
        {
            if (trim($email) != '' && $validator->{'validEmail'.$dns}(trim($email)) === false)
            {
                return false;
            }
        }
        
        return true;
    }
    
}