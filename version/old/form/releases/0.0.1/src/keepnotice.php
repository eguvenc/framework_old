<?php
namespace Form\Src {

    // --------------------------------------------------------------------

    /**
     * Set flash notice.
     * 
     * @param string $message set notification message.
     * @return void
     */
    function keepNotice($error = 'error', $suffix = '')
	{
        $sess              = (isset(getInstance()->sess)) ? getInstance()->sess : new Sess;
        $new_flashdata_key = 'flash:new:';
        $old_flashdata_key = 'flash:old:';

        switch ($error)  // set custom notice
        {
            case 'error':
                $value = $sess->get($old_flashdata_key.'errorMessage_'.$suffix);
                $sess->set($new_flashdata_key.'errorMessage_'.$suffix, $value);
                break;
            
            case 'success':
            	$value = $sess->get($old_flashdata_key.'successMessage_'.$suffix);
                $sess->set($new_flashdata_key.'successMessage_'.$suffix, $value);
                break;

            case 'info':
            	$value = $sess->get($old_flashdata_key.'infoMessage_'.$suffix);
                $sess->set($new_flashdata_key.'infoMessage_'.$suffix, $value);
                break;

            default:
            	$value = $sess->get($old_flashdata_key.'_errorMessage'.$suffix);
                $sess->set($new_flashdata_key.'_errorMessage'.$suffix, $value);
                break;
        }
    }
    
}