<?php

return array(
    
    'locale' => [
        'lang' => 'en'                                                  // Captcha language
    ],
    'api' => [
        'key' => [
            'site' => '6LfhOwATAAAAALotZAhFA1hYdkPkm1W0jOshzMRB',       // Api public site key
            'secret' => '6LfhOwATAAAAAFUHxgdGruN5OXtu-3k5bPrkEbTS',     // Api secret key
        ]
    ],
    'user' => [                                                    // Optional
        'autoSendIp' => false                                           // The end user's ip address.
    ],
    'form' => [                                                    // Captcha input configuration.
        'input' => [
            'attributes' => [
                'name' => 'g-recaptcha-response'                        // reCAPTCHA post key.
            ]
        ]
    ]
);

/* End of file recaptcha.php */
/* Location: .app/config/captcha/recaptcha.php */
