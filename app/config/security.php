<?php

return array(
                
    'encryption' => [
        'key' => 'write-your-secret-key',  // If you use the Encryption class you MUST set an encryption key.
    ],
    'csrf' => [                      
        'protection'  => false,            // Enables a CSRF cookie token to be set. When set to true, token will be
        'token_name'  => 'csrf_token',     // checked on a submitted form. If you are accepting user data, it is strongly
        'cookie_name' => 'csrf_cookie',    // recommended CSRF protection be enabled.
        'expire'      => '7200',           // The number in seconds the token should expire.
     ],

);

/* End of file config.php */
/* Location: .app/config/env/local/security.php */