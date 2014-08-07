<?php

namespace Service;

define('LOGGER_QUEUE_CHANNEL', 'Logs');
define('LOGGER_QUEUE_NAME', 'Server1.Logger.File');
define('LOGGER_QUEUE_JOB', 'QueueLogger');

use Obullo\Log\Disabled,
    Obullo\Log\Handler\File,
    Obullo\Log\Handler\Mongo,
    Obullo\Log\Logger as OLogger,
    Obullo\Log\Writer\FileWriter,
    Obullo\Log\Writer\MongoWriter,
    Obullo\Log\Writer\QueueWriter;

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
            $logger->addFilter('priority', 'Obullo\Log\Filter\Priority');
            /*
            |--------------------------------------------------------------------------
            | File Handler
            |--------------------------------------------------------------------------
            */
            $fileHandler = function () use ($c, $logger) { 
                    return new File(
                        $c,
                        $logger,
                        new QueueWriter(
                            $logger,
                            $c->load('service/queue'),
                            array(
                                'channel' =>  LOGGER_QUEUE_CHANNEL,
                                'route' => LOGGER_QUEUE_NAME,
                                'job' => LOGGER_QUEUE_JOB,
                                'delay' => 0,
                            )
                        )
                        // new FileWriter(
                        //     $logger, 
                        //     $c->load('config')['log']
                        // )
                    );
            };
            /*
            |--------------------------------------------------------------------------
            | Mongo Handler
            |--------------------------------------------------------------------------
            */
            $mongoHandler = function () use ($c, $logger) { 
                return new Mongo(
                    $c,
                    $logger,
                    new MongoWriter(
                        $logger,
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
            | Writers
            |--------------------------------------------------------------------------
            | Primary file writer must be available on local server.
            */
            $logger->addWriter(LOGGER_FILE, $fileHandler)->priority(2);
            /*
            |--------------------------------------------------------------------------
            | Handlers
            |--------------------------------------------------------------------------
            | Add your available log handlers
            */
            $logger->addHandler(LOGGER_MONGO, $mongoHandler)->priority(1)->filter('priority', array(LOG_NOTICE, LOG_ALERT));
            /*
            |--------------------------------------------------------------------------
            | Removes file handler and uses second handler as primary 
            | in "production" env.
            |--------------------------------------------------------------------------
            */
            if (ENV == 'prod') {
                $logger->removeWriter(LOGGER_FILE);
                $logger->removeHandler(LOGGER_MONGO);
                $logger->addWriter(LOGGER_MONGO, $mongoHandler);  //  your production log writer
            }
            return $logger;
        };
    }
}

// END Logger class

/* End of file Logger.php */
/* Location: .classes/Service/Logger.php */