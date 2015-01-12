<?php

return array(
    'locale' => array(
        'lang' => 'en'                                                  // Captcha language
    ),
    'api' => array(
        'key' => array(
            'site' => '6LfhOwATAAAAALotZAhFA1hYdkPkm1W0jOshzMRB',       // Api public site key
            'secret' => '6LfhOwATAAAAAFUHxgdGruN5OXtu-3k5bPrkEbTS',     // Api secret key
        )
    ),
    'user' => array(                                                    // Optional
        'autoSendIp' => false                                           // The end user's ip address.
    ),
    'form' => array(                                                    // Captcha input configuration.
        'input' => array(
            'attributes' => array(
                'name' => 'g-recaptcha-response'                        // reCAPTCHA post key.
            )
        )
    )
);

/* End of file recaptcha.php */
/* Location: .app/config/captcha/recaptcha.php */
