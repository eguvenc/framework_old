<?php

namespace Log\Logger;

use Obullo\Log\Handler\SyslogHandler as LogSyslogHandler;

/**
 * Syslog Handler
 * 
 * @category  Log
 * @package   Handler
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/log
 */
Class SyslogHandler
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

            return new LogSyslogHandler(
                $c,
                array(
                    'name' => 'Logger.Handler.Syslog',
                    'facility' => LOG_USER,
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

// END SyslogHandler class

/* End of file SyslogHandler.php */
/* Location: .app/Log/Logger/SyslogHandler.php */