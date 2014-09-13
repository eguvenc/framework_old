<?php
/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
| Configuration file
|
*/
return array(
    'temporayStorage' => array(
        'lifetime' => 3600,  			// 1 hour
        'rememberMeSeconds' => 604800,  // Remember me ttl for session reminder class. By default " 1 Week ".
    )
);

/* End of file auth.php */
/* Location: .app/env/local/auth.php */