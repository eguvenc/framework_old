<?php

namespace Log;

/**
 * Define Your Log Constants
 *
 * @category  Log
 * @package   Log
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/log
 */
Class Constants
{
    /**
     * Queue exchange name
     */
    const QUEUE_CHANNEL = 'Logs';

    /**
     * Queue separator
     */
    const QUEUE_SEPARATOR = '.logger.';

    /**
     * Queue worker class namespace
     */
    const QUEUE_WORKER = 'Workers\QueueLogger';
}


/* End of file Constants.php */
/* Location: .app/classes/Log/Constants.php */