<?php

namespace Log\Env\Prod;

use Obullo\Log\LogService,
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

            if ( ! $c->load('config')['log']['enabled']) {  // Use disabled handler if config disabled.
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
            $log->registerHandler('mongo', 'Log\Handlers\Queue\Mongo', 5);
            $log->registerHandler('email', 'Log\Handlers\Queue\Email', 4);
            /*
            |--------------------------------------------------------------------------
            | Add Writer - Primary file writer should be available on local server.
            |--------------------------------------------------------------------------
            */
            if (defined('STDIN')) { 
                $log->addWriter('mongo')->filter('priority.notIn', array(LOG_DEBUG, LOG_INFO)); // Cli
            } else {
                $log->addWriter('mongo')->filter('priority.notIn', array(LOG_INFO))->filter('input.filter'); // Http
            }
            return $log;
        };
    }
}

// END Logger class

/* End of file Logger.php */
/* Location: .classes/Log/Env/Prod/Logger.php */