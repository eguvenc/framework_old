<?php

namespace Event;

use Obullo\Container\Container,
    Obullo\Event\EventListenerInterface;

/**
 * Request - response handler
 *
 * @category  EventListener
 * @package   Request
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/event
 */
Class Request implements EventListenerInterface
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
     */
    public function __construct(Container $c)
    {
        $this->c = $c;
        $this->router = $this->c['router'];
        $this->logger = $this->c->load('logger');
    }

    /**
     * Before the run load and index methods of controller
     * 
     * @param object $class  Controller
     * @param object $filter Blocks\Annotations\Filter
     * 
     * @return void
     */
    // public function onBeforeController($class, $filter)
    // {
    //     $this->router->initFilters('before', $filter);  // Initialize ( exec ) registered router ( before ) filters
    //     $class = null;
    // }

    /**
     * After the run index method of controller
     * 
     * @param object $class  Controller
     * @param object $filter \Blocks\Annotations\Filter
     * 
     * @return void
     */
    // public function onAfterController($class, $filter)
    // {
    //     $this->router->initFilters('after', $filter);  // Initialize ( exec ) registered router ( after ) filters
    //     $class = null;
    // }

    /**
     * After the run load() method of controller
     * 
     * @param object $class  Controller
     * @param object $filter \Blocks\Annotations\Filter
     * 
     * @return void
     */
    // public function onLoad($class, $filter)
    // {
    //     $this->router->initFilters('load', $filter);  // Initialize ( exec ) registered router ( after ) filters
    //     $class = null;
    // }

    /**
     * After $this->url->redirect() method
     *
     * @param string $uri    redirect uri url
     * @param string $method redirect method ( header location )
     * 
     * @return void
     */
    public function beforeRedirect($uri, $method)
    {
        $method = null;
        $this->onAfterResponse('Header redirect', array('uri' => $uri));  // Add final response info
    }

    /**
     * On Before Http Request
     * 
     * @return void
     */
    public function onBeforeRequest()
    {
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
         *  Log headers
         * ------------------------------------------------------
         */
        $this->logger->debug('$_REQUEST_URI: ' . $this->c['uri']->getRequestUri(), array(), 10);
        $this->logger->debug('$_COOKIE: ', $_COOKIE, 9);
        $this->logger->debug('$_POST: ', $_POST, 9);
        $this->logger->debug('$_GET: ', $_GET, 9);
    }

    /**
     * Executed when you use annotation method filter
     * 
     * @param object $object     allowed method parameter(s) ( get, post, put, delete )
     * @param string $httpMethod valid request method e.g. post
     * 
     * @return void
     */
    public function onMethod($object, $httpMethod)
    {
        $allowedMethods = (array)$object; // $event->fire() method does not allow to send arrays 
                                          // as one parameter thats why we send params as object.

        if ( ! in_array($httpMethod, $allowedMethods)) {
            $this->c['response']->setHttpResponse(405)->showError(
                sprintf("Http %s method not allowed.", ucfirst($httpMethod))
            );
        }
    }

    /**
     * On After Htttp Response
     *
     * @param string $message log message
     * @param string $extra   benchmark data
     * 
     * @return void
     */
    public function onAfterResponse($message = 'Final output sent to browser', $extra = array())
    {
        $end = microtime(true) - $_SERVER['REQUEST_TIME_START'];  // End Timer

        if ($this->c['config']['log']['extra']['benchmark']) {     // Do we need to generate benchmark data ?
            $usage = 'memory_get_usage() function not found on your php configuration.';
            if (function_exists('memory_get_usage') AND ($usage = memory_get_usage()) != '') {
                $usage = round($usage/1024/1024, 2). ' MB';
            }
            $extra['time'] = number_format($end, 4);
            $extra['memory'] = $usage;
        }
        $this->logger->debug($message, $extra, -99);
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
        $event->listen('before.request', 'Event\Request.onBeforeRequest');
        $event->listen('after.response', 'Event\Request.onAfterResponse');
        $event->listen('before.controller', 'Event\Request.onBeforeController');
        $event->listen('after.controller', 'Event\Request.onAfterController');
        $event->listen('on.load', 'Event\Request.onLoad');
        $event->listen('on.method', 'Event\Request.onMethod');
        $event->listen('before.redirect', 'Event\Request.beforeRedirect');
    }

}

// END Request class

/* End of file Request.php */
/* Location: .Event/Request.php */