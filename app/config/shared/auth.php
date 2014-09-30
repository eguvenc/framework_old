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
        'token' => array(
            'name' => '__ot',    // Cookie name, change it for security
            'refresh' => 10,    // Every 5 minutes
            'path' => '/',
            'secure' => false,
            'httpOnly' => false,
            'prefix' => '',
            'expire' => 6 * 30 * 24 * 3600,  // Default " 6 Months ". Should be same with rememberMeSeconds value.
        ),
        'passwordNeedsRehash' => array(
            'cost' => 10
        ),
    ),
    'login' => array(
        'rememberMeSeconds' => 6 * 30 * 24 * 3600,         // Remember me ttl for session reminder class. Default " 1 Year ".
        'regenerateSessionId' => true,               // Regenerate session id upon new logins.
        'deleteOldSessionAfterRegenerate' => false,  // Destroy old session data after regenerate the new session id upon new logins
    )
);

/* End of file auth.php */
/* Location: .app/env/local/auth.php */