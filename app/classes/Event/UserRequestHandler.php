<?php

namespace Event;

/**
 * User request - response handler
 *
 * @category  Event
 * @package   UserRequestHandler
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/event
 */
Class UserRequestHandler
{
    /**
     * Container
     * 
     * @var object
     */
    protected $c;

    /**
     * Constructor
     *
     * @param object $c container
     * 
     * @return void
     */
    public function __construct($c)
    {
        $this->c = $c;
    }

    /**
     * On Before Http Request
     * 
     * @return void
     */
    public function onBeforeRequest()
    {
        $logger = $this->c->load('service/logger');  // Sanitize inputs
 
        if ($this->c['config']['uri']['queryStrings'] == false) {  // Is $_GET data allowed ? 
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
        $logger->debug('$_REQUEST_URI: ' . $this->c->load('uri')->getRequestUri(), array(), 10);
        $logger->debug('$_COOKIE: ', $_COOKIE, 9);
        $logger->debug('$_POST: ', $_POST, 9);
        $logger->debug('$_GET: ', $_GET, 9);
        $logger->debug('Global POST and COOKIE data sanitized', array(), 10);
    }

    /**
     * On After Htttp Response
     * 
     * @param integer $start start of the response time
     * 
     * @return void
     */
    public function onAfterResponse($start)
    {
        $logger = $this->c->load('service/logger');

        $end = microtime(true) - $start;  // End Timer
        $extra = array();
        if ($this->c['config']['log']['extra']['benchmark']) {     // Do we need to generate benchmark data ? If so, enable and run it.
            $usage = 'memory_get_usage() function not found on your php configuration.';
            if (function_exists('memory_get_usage') AND ($usage = memory_get_usage()) != '') {
                $usage = round($usage/1024/1024, 2). ' MB';
            }
            $extra = array('time' => number_format($end, 4), 'memory' => $usage);
        }
        $logger->debug('Final output sent to browser', $extra, -99);
    }

    /**
     * Event subscribe
     * 
     * @param object $event object
     * 
     * @return void
     */
    public function subscribe($event)
    {
        $event->listen('before.request', 'Event\UserRequestHandler.onBeforeRequest');
        $event->listen('after.response', 'Event\UserRequestHandler.onAfterResponse');
    }

}

// END UserRequestHandler class

/* End of file UserRequestHandler.php */
/* Location: .Event/UserRequestHandler.php */