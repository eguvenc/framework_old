<?php

namespace Service;

define('LOGGER_NAME', '.Logger.');
define('LOGGER_CHANNEL', 'Logs');
define('LOGGER_JOB', 'Workers\QueueLogger');

use Obullo\Log\LogService,
    Obullo\Log\Handler\DisabledHandler;

/**
 * Log Service
 *
 * @category  Service
 * @package   Logger
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/services
 */
Class Logger implements ServiceInterface
{
    /**
     * Registry
     *
     * @param object $c container
     * 
     * @return void
     */
    public function register($c)
    {
        $c['logger'] = function () use ($c) {

            if ( ! $c->load('config')['log']['enabled']) {  // Use disabled handler if config disabled.
                return new DisabledHandler;
            }
            $logger = new LogService($c, $c->load('config')['log']);
            /*
            |--------------------------------------------------------------------------
            | Register Filters
            |--------------------------------------------------------------------------
            */
            $logger->registerFilter('priority', 'Log\Filters\PriorityFilter');
            /*
            |--------------------------------------------------------------------------
            | Register Handlers
            |--------------------------------------------------------------------------
            */
            $logger->registerHandler(LOGGER_FILE, 'Log\Handlers\FileHandler\CartridgeFileWriter');
            $logger->registerHandler(LOGGER_MONGO, 'Log\Handlers\MongoHandler\CartridgeMongoWriter');
            $logger->registerHandler(LOGGER_EMAIL, 'Log\Handlers\EmailHandler\CartridgeQueueWriter');
            /*
            |--------------------------------------------------------------------------
            | Add Writer - Primary file writer should be available on local server.
            |--------------------------------------------------------------------------
            */
            $logger->addWriter(LOGGER_FILE)->priority(2);
            // $logger->addWriter(LOGGER_MONGO)->priority(5);
            /*
            |--------------------------------------------------------------------------
            | Add Handler - Adds to available log handlers
            |--------------------------------------------------------------------------
            */
            $logger->addHandler(LOGGER_EMAIL)->priority(2)->filter('priority.notIn', array(LOG_DEBUG, LOG_INFO));
            /*
            |--------------------------------------------------------------------------
            | Removes file handler and uses second handler as primary in "production" env.
            |--------------------------------------------------------------------------
            */
            if (ENV == 'prod') {
                $logger->removeWriter(LOGGER_FILE);
                $logger->removeHandler(LOGGER_MONGO);
                $logger->addWriter(LOGGER_MONGO);  //  Your production log writer
            }
            return $logger;
        };
    }
}

// END Logger class

/* End of file Logger.php */
/* Location: .classes/Service/Logger.php */