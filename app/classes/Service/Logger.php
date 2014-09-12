<?php

namespace Service;

define('LOGGER_NAME', '.Logger.');
define('LOGGER_CHANNEL', 'Logs');
define('LOGGER_JOB', 'Workers\QueueLogger');

use Obullo\Log\Handler\DisabledHandler,
    Obullo\Log\Handler\FileHandler,
    Obullo\Log\Handler\MongoHandler,
    Obullo\Log\Handler\EmailHandler,
    Obullo\Log\Logger as LogLogger,
    Obullo\Log\Writer\FileWriter,
    Obullo\Log\Writer\MongoWriter,
    Obullo\Log\Writer\EmailWriter,
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
            $logger = new LogLogger($c, $c->load('config')['log']);
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
                    // new QueueWriter(
                    //     $c->load('service/queue'),
                    //     array(
                    //         'channel' =>  LOGGER_CHANNEL,
                    //         'route' => gethostname(). LOGGER_NAME .'File',
                    //         'job' => LOGGER_JOB,
                    //         'delay' => 0,
                    //     )
                    // )
                    new FileWriter(
                        $c->load('config')['log']
                    )
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
                    new QueueWriter(
                        $c->load('service/queue'),
                        array(
                            'channel' => LOGGER_CHANNEL,
                            'route' => gethostname(). LOGGER_NAME .'Mongo',
                            'job' => LOGGER_JOB,
                            'delay' => 0,
                            'format' => array(
                                'context' => 'array',  // json
                                'extra'   => 'array'   // json
                            ),
                        )
                    )
                    // new MongoWriter(
                    //     $c->load('service/provider/mongo', 'db'),  // Mongo client instance
                    //     array(
                    //         'database' => 'db',
                    //         'collection' => 'logs',
                    //         'save_options' => null,
                    //         'format' => array(
                    //             'context' => 'array',  // json
                    //             'extra'   => 'array'   // json
                    //         ),
                    //     )
                    // )
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
                            'route' => gethostname(). LOGGER_NAME .'Email',
                            'job' => LOGGER_JOB,
                            'delay' => 0,
                            'format' => array(
                                'context' => 'array',  // json
                                'extra'   => 'array'   // json
                            ),
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
            // $logger->addWriter(LOGGER_MONGO, $MONGO_HANDLER)->priority(5); // ->filter('priority.notIn', array(LOG_DEBUG)); 
            
            /*
            |--------------------------------------------------------------------------
            | Handlers
            |--------------------------------------------------------------------------
            | Add your available log handlers
            */
            $logger->addHandler(LOGGER_EMAIL, $EMAIL_HANDLER)->priority(2)->filter('priority.notIn', array(LOG_DEBUG, LOG_INFO));
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