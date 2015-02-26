<?php

namespace Event;

use Obullo\Container\Container;
use Obullo\Event\EventListenerInterface;
use Obullo\Application\Addons\BenchmarkTrait;

Class Redirect implements EventListenerInterface
{
    use BenchmarkTrait;     // You can add / remove addons.

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
        $event->listen('before.redirect', 'Event\Redirect@before');
    }

}

// END Redirect class

/* End of file Redirect.php */
/* Location: .Event/Redirect.php */