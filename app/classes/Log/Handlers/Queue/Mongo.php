<?php

namespace Log\Handlers\Queue;

use Log\Constants,
    Obullo\Log\Handler\MongoHandler;

/**
 * Queue Mongo Handler
 * 
 * @category  Log
 * @package   Handler
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/log
 */
Class Mongo
{
    /**
     * Container
     * 
     * @var object
     */
    protected $c;

    /**
     * Handler closure
     * 
     * @var object
     */
    protected $closure;

    /**
     * Handler priority
     * 
     * @var integer
     */
    protected $priority;

    /**
     * Constructor
     * 
     * @param object  $c        container
     * @param integer $priority priority
     */
    public function __construct($c, $priority = 1)
    {
        $this->closure = function () use ($c) {
            
            return new MongoHandler(
                $c,
                $c->load('service/provider/mongo', 'db'),  // Mongo client instance
                array(
                    'channel' =>  Log\Constants::QUEUE_CHANNEL,
                    'route' => gethostname(). Log\Constants::QUEUE_SEPARATOR .'Mongo',
                    'job' => Log\Constants::QUEUE_WORKER,
                    'delay' => 0,
                    'database' => 'db',
                    'collection' => 'logs',
                    'save_options' => null,
                    'format' => array(
                        'context' => 'array',  // json
                        'extra'   => 'array'   // json
                    ),
                )
            );
        };
        $this->priority = $priority;
    }

    /**
     * Returns to closure data of handler
     * 
     * @return object closure
     */
    public function getHandler()
    {
        return $this->closure;
    }

    /**
     * Handler priority
     * 
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }
}

// END Mongo class

/* End of file Mongo.php */
/* Location: .app/Log/Handlers/Queue/Mongo.php */