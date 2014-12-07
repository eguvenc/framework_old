<?php
/*
|--------------------------------------------------------------------------
| Events
|--------------------------------------------------------------------------
| This file specifies the your application global events.
*/

/*
|--------------------------------------------------------------------------
| Before Application Requests
|--------------------------------------------------------------------------
*/
$c['event']->listen(
	'before.request', 
	function ($c) {
		/*
		|--------------------------------------------------------------------------
		| Sanitize inputs
		|--------------------------------------------------------------------------
		*/
		$logger = $c->load('service/logger');

		if ($c->load('config')['uri']['queryStrings'] == false) {  // Is $_GET data allowed ? 
		    $_GET = array(); // If not we'll set the $_GET to an empty array
		}
		$_SERVER['PHP_SELF'] = strip_tags($_SERVER['PHP_SELF']); // Sanitize PHP_SELF

		// Clean $_COOKIE Data
		// Also get rid of specially treated cookies that might be set by a server
		// or silly application, that are of no use to application anyway
		// but that when present will trip our 'Disallowed Key Characters' alarm
		// http://www.ietf.org/rfc/rfc2109.txt
		// note that the key names below are single quoted strings, and are not PHP variables
		unset(
		    $_COOKIE['$Version'], 
		    $_COOKIE['$Path'], 
		    $_COOKIE['$Domain']
		);
		/*
		 * ------------------------------------------------------
		 *  Log requests
		 * ------------------------------------------------------
		 */
		$logger->debug('$_REQUEST_URI: ' . $c->load('uri')->getRequestUri(), array(), 10);
		$logger->debug('$_COOKIE: ', $_COOKIE, 9);
		$logger->debug('$_POST: ', $_POST, 9);
		$logger->debug('$_GET: ', $_GET, 9);
		$logger->debug('Global POST and COOKIE data sanitized', array(), 10);
	}
);

/*
|--------------------------------------------------------------------------
| After Application Response
|--------------------------------------------------------------------------
*/
$c['event']->listen(
	'after.response', 
	function ($c, $start) {
		
		$logger = $c->load('service/logger');

		$end = microtime(true) - $start;  // End Timer
		$extra = array();
		if ($c->load('config')['log']['extra']['benchmark']) {     // Do we need to generate benchmark data ? If so, enable and run it.
		    $usage = 'memory_get_usage() function not found on your php configuration.';
		    if (function_exists('memory_get_usage') AND ($usage = memory_get_usage()) != '') {
		        $usage = round($usage/1024/1024, 2). ' MB';
		    }
		    $extra = array('time' => number_format($end, 4), 'memory' => $usage);
		}
		$logger->debug('Final output sent to browser', $extra, -99);
	}
);


/* End of file events.php */
/* Location: .events.php */