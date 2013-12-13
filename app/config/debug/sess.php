<?php

/*
|--------------------------------------------------------------------------
| Sess Package Configuration
|--------------------------------------------------------------------------
|
| Session Variables
|
*/
$sess = array(
	
	'cookie_name' => 'ob_session',  // The name you want for the cookie
	'expiration' => 7200,			// The number of SECONDS you want the session to last. By default sessions last 7200 seconds (two hours). Set to zero for no expiration.
	'expire_on_close' => false, 	// Whether to cause the session to expire automatically when the browser window is closed
	'encrypt_cookie' => false,		// Whether to encrypt the cookie
	'driver' => new Sess_Native, 	// Sess_Database
	'get' => new Get,				// Set input object
	'database' => null,				// null, // new Db(); // new Mongo_Db;   Set database object
	'table_name' => 'ob_sessions',	// The name of the session database table
	'match_ip' => false,			// Whether to match the user's IP address when reading the session data
	'match_useragent' => true,		// Whether to match the User Agent when reading the session data
	'time_to_update' => 300			// How many seconds between Framework refreshing "Session" Information"
);

/* End of file sess.php */
/* Location: .app/config/debug/sess.php */