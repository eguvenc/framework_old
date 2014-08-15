<?php

namespace Service;

define('LOGGER_SERVER', 'Server1.Logger.');
define('LOGGER_CHANNEL', 'Logs');
define('LOGGER_JOB', 'QueueLogger');

use Obullo\Log\Handler\DisabledHandler,
    Obullo\Log\Handler\FileHandler,
    Obullo\Log\Handler\MongoHandler,
    Obullo\Log\Handler\EmailHandler,
    Obullo\Log\Logger as OLogger,
    Obullo\Log\Writer\FileWriter,
    Obullo\Log\Writer\MongoWriter,
    Obullo\Log\Writer\EmailWriter,
    Obullo\Log\Writer\QueueWriter;
/*
|--------------------------------------------------------------------------
| Log
|--------------------------------------------------------------------------
| @see Syslog Protocol http://tools.ietf.org/html/rfc5424
|
| Constants:
|
| 0  LOG_EMERG: System is unusable
| 1  LOG_ALERT: Action must be taken immediately
| 2  LOG_CRIT: Critical conditions
| 3  LOG_ERR: Error conditions
| 4  LOG_WARNING: Warning conditions
| 5  LOG_NOTICE: Normal but significant condition
| 6  LOG_INFO: Informational messages
| 7  LOG_DEBUG: Debug-level messages
*/

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

            if ($c->load('config')['log']['enabled'] == false) {  // Use disabled handler if config disabled.
                return new Disabled;
            }
            $logger = new OLogger($c, $c->load('config')['log']);
            /*
            |--------------------------------------------------------------------------
            | Filters
            |--------------------------------------------------------------------------
            | Register your filters here
            */
            $logger->registerFilter('priority', 'Obullo\Log\Filter\Priority');
            /*
            |--------------------------------------------------------------------------
            | File Handler
            |--------------------------------------------------------------------------
            */
            $FILE_HANDLER = function () use ($c) { 
                    return new FileHandler(
                        $c,
                        new QueueWriter(
                            $c->load('service/queue'),
                            array(
                                'channel' =>  LOGGER_CHANNEL,
                                'route' => LOGGER_SERVER .'File',
                                'job' => LOGGER_JOB,
                                'delay' => 0,
                            )
                        )
                        // new FileWriter(
                        //     $c->load('config')['log']
                        // )
                    );
            };
            /*
            |--------------------------------------------------------------------------
            | Mongo Handler
            |--------------------------------------------------------------------------
            */
            $MONGO_HANDLER = function () use ($c) { 
                return new MongoHandler(
                    $c,
                    new MongoWriter(
                        $c->load('service/provider/mongo', 'db'),  // Mongo client instance
                        array(
                        'database' => 'db',
                        'collection' => 'logs',
                        'save_options' => null
                        )
                    )
                );
            };
            /*
            |--------------------------------------------------------------------------
            | Email Handler
            |--------------------------------------------------------------------------
            */
            $EMAIL_HANDLER = function () use ($c) { 
                return new EmailHandler(
                    $c,
                    new QueueWriter(
                        $c->load('service/queue'),
                        array(
                            'channel' =>  LOGGER_CHANNEL,
                            'route' => LOGGER_SERVER .'Email',
                            'job' => LOGGER_JOB,
                            'delay' => 0,
                        )
                    )
                );
            };
            /*
            |--------------------------------------------------------------------------
            | Writers
            |--------------------------------------------------------------------------
            | Primary file writer should be available on local server.
            */
            $logger->addWriter(LOGGER_FILE, $FILE_HANDLER)->priority(2);
            /*
            |--------------------------------------------------------------------------
            | Handlers
            |--------------------------------------------------------------------------
            | Add your available log handlers
            */
            $logger->addHandler(LOGGER_MONGO, $MONGO_HANDLER)->priority(1);
            $logger->addHandler(LOGGER_EMAIL, $EMAIL_HANDLER)->priority(2)->filter('priority', array(LOG_ERR, LOG_CRIT, LOG_ALERT, LOG_NOTICE, LOG_WARNING, LOG_EMERG));
            /*
            |--------------------------------------------------------------------------
            | Removes file handler and uses second handler as primary 
            | in "production" env.
            |--------------------------------------------------------------------------
            */
            if (ENV == 'prod') {
                $logger->removeWriter(LOGGER_FILE);
                $logger->removeHandler(LOGGER_MONGO);
                $logger->addWriter(LOGGER_MONGO, $MONGO_HANDLER);  //  your production log writer
            }
            return $logger;
        };
    }
}

// END Logger class

/* End of file Logger.php */
/* Location: .classes/Service/Logger.php */