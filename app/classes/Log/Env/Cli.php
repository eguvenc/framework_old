<?php

namespace Log\Env;

use Service\ServiceInterface,
    Log\Constants,
    Obullo\Log\LogService;

/**
 * Log Service
 *
 * @category  Service
 * @package   Logger
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT
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

            $service = new LogService($c, $c['config']);
            /*
            |--------------------------------------------------------------------------
            | Register Filters
            |--------------------------------------------------------------------------
            */
            $service->logger->registerFilter('priority', 'Log\Filters\PriorityFilter');
            $service->logger->registerFilter('input', 'Log\Filters\InputFilter');
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
/* Location: .classes/Log/Env/Cli.php */