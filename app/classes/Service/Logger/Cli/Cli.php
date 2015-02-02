<?php

namespace Service\Logger\Cli;

use Obullo\Container\Container,
    Obullo\Log\LogServiceProvider,
    Obullo\ServiceProvider\ServiceInterface;

/**
 * Log Service
 *
 * @category  Service
 * @package   Logger
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
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
    public function register(Container $c)
    {
        $c['logger'] = function () use ($c) {

            $serviceProvider = new LogServiceProvider($c);
            $logger = $serviceProvider->getLogger();
            /*
            |--------------------------------------------------------------------------
            | Set configuration
            |--------------------------------------------------------------------------
            */
            $logger->setParams(
                [
                    'default' => array(
                        'channel' => 'system',       // Default channel name should be general.
                    ),
                    'file' => array(
                        'path' => array(
                            'http'  => 'data/logs/http.log',  // Http requests log path  ( Only for File Handler )
                            'cli'   => 'data/logs/cli.log',   // Cli log path  
                            'ajax'  => 'data/logs/ajax.log',  // Ajax log path
                        )
                    ),
                    'format' => array(
                        'line' => '[%datetime%] %channel%.%level%: --> %message% %context% %extra%\n',  // This format just for line based log drivers.
                        'date' =>  'Y-m-d H:i:s',
                    ),
                    'extra' => array(
                        'queries'   => true,       // If true "all" SQL Queries gets logged.
                        'benchmark' => true,       // If true "all" Application Benchmarks gets logged.
                    ),
                    'queue' => array(
                        'channel' => 'Log',
                        'route' => gethostname(). '.Logger',
                        'worker' => 'Workers\Logger',
                        'delay' => 0,
                        'workers' => array(
                            'logging' => false     // On / Off Queue workers logging functionality. See the Queue package docs.
                                                   // You can turn on logs if you want to set workers as service.
                        ), 
                    )
                ]
            );
            /*
            |--------------------------------------------------------------------------
            | Register Filters
            |--------------------------------------------------------------------------
            */
            $logger->registerFilter('priority', 'Log\Filters\PriorityFilter');
            $logger->registerFilter('input', 'Log\Filters\InputFilter');
            /*
            |--------------------------------------------------------------------------
            | Register Handlers
            |--------------------------------------------------------------------------
            */
            $logger->registerHandler(5, 'file');
            $logger->registerHandler(4, 'mongo')->filter('priority.notIn', array(LOG_DEBUG));
            $logger->registerHandler(3, 'email')->filter('priority.notIn', array(LOG_DEBUG));
            /*
            |--------------------------------------------------------------------------
            | Add Writers - Primary file writer should be available on local server
            |--------------------------------------------------------------------------
            */
            $logger->addWriter('file')->filter('priority.notIn', array(LOG_INFO));
            
            return $logger;
        };
    }
}

// END Cli class

/* End of file Cli.php */
/* Location: .classes/Service/Logger/Cli/Cli.php */