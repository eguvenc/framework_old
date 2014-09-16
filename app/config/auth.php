<?php
/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
| Configuration file
|
*/
return array(
    'temporaryStorage' => array(
        'driver' => 'Cache',
        'lifetime' => 3600,             // 1 hour storage life time
        'rememberMeSeconds' => 604800,  // Remember me ttl for session reminder class. By default " 1 Week ".
        'regenerateSessionId' => true,  // Regenerate session id upon new logins
        'deleteOldSessionAfterRegenerate' => false,  // Destroy old session data after regenerate the new session id.
    ),
);

/* End of file auth.php */
/* Location: .app/env/local/auth.php */