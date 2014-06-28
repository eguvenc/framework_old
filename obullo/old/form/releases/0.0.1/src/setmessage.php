<?php
namespace Form\Src {

    // --------------------------------------------------------------------

    /**
     * Set flash notice.
     * 
     * @param string $key set notification message or field.
     * @param string $val set notification message value.
     * 
     * @return void
     */
    function setMessage($key = '', $val = '')
    {
        if (empty($val) OR is_numeric($val)) {  //  set form validation message

            $form       = \Form::getConfig();
            $formObject = getInstance()->form;
            $message    = ( ! empty($key)) ? $key : $form['response']['error'];
            $template = $val == 1 ? $form['notifications']['successMessage'] : $form['notifications']['errorMessage']; 

            $formObject->_formMessages['success'] = (empty($val)) ? 0 : $val;            
            $formObject->_formMessages['message'] = sprintf($template, translate($message));
        
        } else { 
            getInstance()->validator->setMessage($key, $val); // use validator object set message
        }
    }
    
}