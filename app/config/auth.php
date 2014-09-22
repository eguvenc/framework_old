<?php
/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
| Configuration file
|
*/
return array(
    'adapter' => 'AssociativeArray',
    'memory' => array(          // Keeps user identitiy data in your cache driver.
        'storage' => 'Cache',   // Storage driver uses cache service.
        'block' => array(
            'permanent' => array(
                'lifetime' => 3600  // 1 hour is default storage life time. if remember choosed we use "rememberMeSeconds" value as lifetime otherwise default.
            ),
            'temporary'  => array(
                'lifetime' => 300  // 5 minutes is default temporary login lifetime.
            )
        )
    ),
    'security' => array(    
        'cookie' => array(
            'enabled' => false,  // If true $auth->isAuthenticated() method check user agent and credentials match with security cookie.
            'name' => '__os',    // Cookie name, change it for security
            'hash' => 'sha256',  // sha512
            'expire' => 315360000000,  // Default " 1 Year ".
        ),
        'passwordNeedsRehash' => array(
            'cost' => 10
        ),
    ),
    'rememberMeSeconds' => 315360000000,         // Remember me ttl for session reminder class. By default " 1 Year ".
    'regenerateSessionId' => true,               // Regenerate session id upon new logins.
    'deleteOldSessionAfterRegenerate' => false,  // Destroy old session data after regenerate the new session id upon new logins
);

/* End of file auth.php */
/* Location: .app/env/local/auth.php */