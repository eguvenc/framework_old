<?php

namespace Log\Env\Local;

use Service\ServiceInterface,
    Obullo\Log\LogService,
    Obullo\Log\Handler\NullHandler;

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

            if ( ! $c->load('config')['log']['enabled']) {  // Use null handler if config disabled.
                return new NullHandler;
            }
            $log = new LogService($c, $c->load('config')['log']);
            /*
            |--------------------------------------------------------------------------
            | Register Filters
            |--------------------------------------------------------------------------
            */
            $log->registerFilter('priority', 'Log\Filters\PriorityFilter');
            $log->registerFilter('input', 'Log\Filters\InputFilter');
            /*
            |--------------------------------------------------------------------------
            | Register Handlers
            |--------------------------------------------------------------------------
            */
            $log->registerHandler('file', 'Log\Handlers\Simple\File', 5);
            $log->registerHandler('mongo', 'Log\Handlers\Simple\Mongo', 4);
            $log->registerHandler('email', 'Log\Handlers\Simple\Email', 3);
            /*
            |--------------------------------------------------------------------------
            | Add Writer - Primary file writer should be available on local server.
            |--------------------------------------------------------------------------
            */
            if (defined('STDIN')) { 
                $log->addWriter('file')->filter('priority.notIn', array(LOG_DEBUG, LOG_INFO)); // Cli
            } else {
                $log->addWriter('file')->filter('priority.notIn', array(LOG_INFO))->filter('input.filter'); // Http
            }
            return $log;
        };
    }
}

// END Logger class

/* End of file Logger.php */
/* Location: .classes/Log/Env/Local/Logger.php */