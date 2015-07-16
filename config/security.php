<?php

return array(

    /*
    | -------------------------------------------------------------------
    | Security Package Configuration
    | -------------------------------------------------------------------
    | Sets your encryption key and protection settings.
    |
    */
    'encryption' => [
        'key' => 'write-your-secret-key',  // If you use the Encryption class you MUST set an encryption key.
    ],

    'csrf' => [                      
        'protection' => true,          // Enables a CSRF session token to be set. When set to true, token will be
        'token' => [                   // checked on a submitted form. If you are accepting user data, it is strongly
            'name' => 'csrf_token',    // recommended CSRF protection be enabled.
            'refresh' => 30,           // Refresh the csrf token every x seconds default 30 seconds.
        ],    
     ],                                 

);