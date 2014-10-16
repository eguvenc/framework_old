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
        'storage' => 'Redis',   // Storage driver uses cache package
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
            'name' => '__ot',  // Cookie name, change it if you want
            'refresh' => 60,   // Every 1 minutes do the cookie validation
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
        'rememberMe'  => array(
            'cookie' => array(
                'name' => '__rm',
                'path' => '/',
                'secure' => false,
                'httpOnly' => false,
                'prefix' => '',
                'expire' => 6 * 30 * 24 * 3600,  // Default " 6 Months ". Should be same with rememberMeSeconds value.
            )
        ),
        'session' => array(
            'regenerateSessionId' => true,               // Regenerate session id upon new logins.
            'deleteOldSessionAfterRegenerate' => false,  // Destroy old session data after regenerate the new session id upon new logins
        )
    ),
    'activity' => array(
        'singleSignOff' => false,  // Single sign-off is the property whereby a single action of signing out terminates access to multiple agents.
    )
);

/* End of file auth.php */
/* Location: .app/config/shared/auth.php */