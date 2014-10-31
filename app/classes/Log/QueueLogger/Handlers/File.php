<?php

namespace Log\Handlers\Queue;

use Log\Constants,
    Obullo\QueueLogger\Handler\FileHandler;

/**
 * FileHandler
 * 
 * @category  Log
 * @package   Handler
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/log
 */
Class File
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

            return new FileHandler(
                $c,
                $c->load('service/queue'),
                array(
                    'channel' =>  Log\Constants::QUEUE_CHANNEL,
                    'route' => gethostname(). Log\Constants::QUEUE_SEPARATOR .'File',
                    'job' => Log\Constants::QUEUE_WORKER,
                    'delay' => 0,
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

// END File class

/* End of file File.php */
/* Location: .app/Log/Handlers/Queue/File.php */