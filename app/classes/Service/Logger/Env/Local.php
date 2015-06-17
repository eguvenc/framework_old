<?php

namespace Service\Logger\Env;

use Obullo\Service\ServiceInterface;
use Obullo\Container\ContainerInterface;

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
class Local implements ServiceInterface
{
    /**
     * Registry
     *
     * @param object $c container
     * 
     * @return void
     */
    public function register(ContainerInterface $c)
    {
        $c['logger'] = function () use ($c) {
            
            $logger = $c['app']->provider('logger')->get(
                [
                    'queue' => [
                        'enabled' => false,
                        'channel' => 'Log',
                        'route' => gethostname(). '.Logger',
                        'worker' => 'Workers\Logger',
                        'delay' => 0,
                        'workers' => [
                            'logging' => false     // On / Off Queue workers logging functionality. See the Queue package docs.
                                                   // You may want to turn on logs if you want to set workers as service in another application.
                        ],
                    ]
                ]
            );
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
            $logger->registerHandler(5, 'file');
            $logger->registerHandler(4, 'mongo')->filter('priority@notIn', array(LOG_DEBUG));
            $logger->registerHandler(3, 'email')->filter('priority@notIn', array(LOG_DEBUG));
            /*
            |--------------------------------------------------------------------------
            | Add Writers - Primary file writer should be available on local server
            |--------------------------------------------------------------------------
            */
            $logger->addWriter('file')->filter('priority@notIn', array());
            return $logger;
        };
    }
}