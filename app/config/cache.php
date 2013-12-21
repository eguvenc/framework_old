<?php

/*
|--------------------------------------------------------------------------
| Cache Package Configuration
|--------------------------------------------------------------------------
| Prototype: 
|
|   $cache['key'] = value;
| 
*/

$cache = array(
			   'driver'  => 'memcached',
			   'servers' => array(
								  'hostname' => 'localhost',
								  'port'     => '11211',
								  'weight'   => '1'			// The weight parameter effects the consistent hashing 
								  							// used to determine which server to read/write keys from.
								  ),
			   
				'cache_path' =>  '/data/temp/cache/';  // Just cache file .data/temp/cache
			   );


/* End of file cache.php */
/* Location: .app/config/cache.php */