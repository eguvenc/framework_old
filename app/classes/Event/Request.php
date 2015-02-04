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
        $this->logger = $this->c['logger'];
    }

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
     * Event subscribe
     * 
     * @param object $event object
     * 
     * @return void
     */
    public function subscribe($event)
    {
        $event->listen('before.redirect', 'Event\Request.beforeRedirect');
    }

}

// END Request class

/* End of file Request.php */
/* Location: .Event/Request.php */