<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @license         public
 * @since           Version 1.0
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Obullo Email Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Obullo Team
 * @link        
 */

// ------------------------------------------------------------------------

/**
* Validate email address
*
* @access	public
* @return	bool
*/
if( ! function_exists('valid_email') ) 
{
    function valid_email($address, $check_dns = FALSE)
    {
        if($check_dns)
        {
            if(preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address))
            {
                list($username, $domain) = explode('@', $address);
            
                if( ! checkdnsrr($domain,'MX'))
                {
                    return FALSE;
                }

                return TRUE;
            }
        }
        
        return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
    }
}

// ------------------------------------------------------------------------

/**
* Send an email
*
* @access	public
* @return	bool
*/
if( ! function_exists('send_email') ) 
{	
    function send_email($recipient, $subject = 'Test email', $message = 'Hello World')
    {
        return mail($recipient, $subject, $message);
    }
}

/* End of file email.php */
/* Location: ./obullo/helpers/email.php */