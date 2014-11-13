<?php

namespace Log\Env\QueueLogger;

use Service\ServiceInterface,
    Log\Constants,
    Obullo\Log\LogService,
    Obullo\QueueLogger\Handler\FileHandler;

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
Class Cli implements ServiceInterface
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

            $service = new LogService(
                $c,
                array(
                    'queue' => array(
                        'channel' => Constants::QUEUE_CHANNEL,
                        'route' => gethostname(). Constants::QUEUE_SEPARATOR .'%s',
                        'job' => Constants::QUEUE_WORKER,
                        'delay' => 0,
                    ),
                    'log' => $c->load('config')['log']
                )
            );
            /*
            |--------------------------------------------------------------------------
            | Register Filters
            |--------------------------------------------------------------------------
            */
            $service->logger->registerFilter('priority', 'PriorityFilter')->registerFilter('input', 'InputFilter');
            /*
            |--------------------------------------------------------------------------
            | Register Handlers
            |--------------------------------------------------------------------------
            */
            $service->logger->registerHandler(5, 'file');
            $service->logger->registerHandler(4, 'mongo')->filter('priority.notIn', array(LOG_DEBUG));
            $service->logger->registerHandler(3, 'email')->filter('priority.notIn', array(LOG_DEBUG));
            /*
            |--------------------------------------------------------------------------
            | Add Writer - Primary file writer should be available on local server
            |--------------------------------------------------------------------------
            */
            $service->logger->addWriter('file')->filter('priority.notIn', array(LOG_INFO));
            
            return $service->logger;
        };
    }
}

// END Cli class

/* End of file Cli.php */
/* Location: .classes/Log/Env/QueueLogger/Cli.php */