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
								  'weight'   => '1'
								  ),
				'cache_path' => DATA .'temp'. DS .'cache'. DS // Just cache file .data/temp/cache
			   );

/* End of file cache.php */
/* Location: .app/config/cache.php */