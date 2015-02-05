<?php

namespace Event;

use Obullo\Container\Container,
    Obullo\Event\EventListenerInterface,
    Obullo\Application\Addons\BenchmarkTrait;

/**
 * Global event listener
 *
 * @category  EventListener
 * @package   Request
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/event
 */
Class Redirect implements EventListenerInterface
{
    use BenchmarkTrait;

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
    }

    /**
     * After 
     *
     * @param string $uri    redirect uri url
     * @param string $method redirect method ( header location )
     * 
     * @return void
     */
    public function before($uri, $method)
    {
        $this->benchmarkEnd('Header redirect', array('uri' => $uri, 'method' => $method));  // Add final response info
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
        $event->listen('before.redirect', 'Event\Redirect.before');
    }

}

// END Redirect class

/* End of file Redirect.php */
/* Location: .Event/Redirect.php */