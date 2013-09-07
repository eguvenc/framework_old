<?php

/*
|--------------------------------------------------------------------------
|  Sess Package Configuration
|--------------------------------------------------------------------------
|
|  Session Variables
|
| 'cookie_name'          = The name you want for the cookie
| 'expiration'           = The number of SECONDS you want the session to last.
|   by default sessions last 7200 seconds (two hours).  Set to zero for no expiration.
| 'expire_on_close'      = Whether to cause the session to expire automatically when the browser window is closed
| 'encrypt_cookie'       = Whether to encrypt the cookie
| 'driver'               = Choose your driver ( cookie | database | mongo | native )
| 'db_var'               = The database variable default is db
| 'table_name'           = The name of the session database table
| 'match_ip'             = Whether to match the user's IP address when reading the session data
| 'match_useragent'      = Whether to match the User Agent when reading the session data
| 'time_to_update'	 = how many seconds between Obullo refreshing Session Information
|
*/
$sess['cookie_name']      = 'ob_session';
$sess['expiration']       = 7200;
$sess['expire_on_close']  = false;
$sess['encrypt_cookie']   = false;
$sess['driver']           = 'native';  // native | database | mongo 
$sess['db_var']           = 'db';            
$sess['table_name']       = 'ob_sessions';
$sess['match_ip']         = false;
$sess['match_useragent']  = true;
$sess['time_to_update']   = 300;


/* End of file sess.php */
/* Location: .app/config/sess.php */