<?php
namespace Form\Src {

    // --------------------------------------------------------------------

    /**
     * Set flash notice.
     * 
     * @param string $message set notification message.
     * @return void
     */
    function setNotice($message, $key = '0', $suffix = null)
    {
        $sess   = (isset(getInstance()->sess)) ? getInstance()->sess : new \Sess;
        $suffix = ($suffix === null) ? uniqid() : $suffix;

        if(is_bool($key)) // Boolean support
        {
            $key = ($key) ? '1' : '0';
        }

        $message = translate($message);

        switch ($key)  // set custom notice
        {
            case '0':
                $sess->setFlash('errorMessage_'.$suffix, $message);
                break;
            
            case '1':
                $sess->setFlash('successMessage_'.$suffix, $message);
                break;

            case '2':
                $sess->setFlash('infoMessage_'.$suffix, $message);
                break;
        }
    }
    
}