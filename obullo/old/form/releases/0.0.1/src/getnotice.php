<?php
namespace Form\Src {

    // --------------------------------------------------------------------

    /**
     * Get notification message you set it before.
     *
     * @param string $errorKey 'success' or 'errorMessage'
     * @return string notification
     */
    function getNotice($error = '', $suffix = '')
    {
        $sess         = new \Sess;
        $sess         = $sess::$driver;
        $form         = getConfig('form');
        $errorKey     = 'errorMessage';
        $noticeString = '';

        if(empty($error) AND empty($suffix))  // Auto detect notices ..
        {
            /////////////////////////
            // get all notices ... //
            /////////////////////////
            
            $oldKey = 'flash:old:';
            foreach ($sess->getAllData() as $key => $value)
            {
                if(strpos($key, $oldKey) === 0)
                {
                    preg_match('/flash:old:(.*?)Message_(.*?)$/',$key,$match);

                    $noticeKey = $match[1];
                    $suffix    = $match[2];

                    if(empty($error)) // If parameter empty check last sess flash notice
                    {
                        $noticeString.= _getNoticeKey($noticeKey, $form, $sess, $suffix);
                    }
                    else
                    {
                        if($error == $noticeKey)
                        {
                            $noticeString.= _getNoticeKey($noticeKey, $form, $sess, $suffix);
                        }
                    }
                }
            }
        } else {
            return _getNoticeKey($error, $form, $sess, $suffix);
        }

        return $noticeString;
    }

    // --------------------------------------------------------------------

    /**
     * Get notification error
     * 
     * @param  string $error  
     * @param  string $prefix 
     * @param  array $form  
     * @param  object $sess   
     * @return string        
     */
    function _getNoticeKey($error = '', $form, $sess = null, $suffix)
    {
        switch ($error)  // get custom notice
        {
        case 'error':
            $errorKey = 'errorMessage';
            $notice = $sess->getFlash('errorMessage_'.$suffix);
            break;
        
        case 'success':
            $errorKey = 'successMessage';
            $notice = $sess->getFlash('successMessage_'.$suffix);
            break;

        case 'info':
            $errorKey = 'infoMessage';
            $notice = $sess->getFlash('infoMessage_'.$suffix);
            break;

        default:
            $errorKey = 'errorMessage';
            $notice = $sess->getFlash('errorMessage_'.$suffix);
            break;
        }
        if ( ! empty($notice)) {
            return sprintf($form['notifications'][$errorKey], $notice);
        }
    }

}