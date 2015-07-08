<?php

namespace Service\Logger\Env;

use Obullo\Log\LogManager;
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
            
            $parameters = [
                'queue' => [
                    'enabled' => false,
                    'channel' => 'log',
                    'route' => 'logger.1',
                    'delay' => 0,
                ]
            ];
            $manager = new LogManager($c);
            $manager->setConfiguration($parameters);
            $logger = $manager->getLogger();
            /*
            |--------------------------------------------------------------------------
            | Register Filters
            |--------------------------------------------------------------------------
            */
            $logger->registerFilter('priority', 'Obullo\Log\Filters\PriorityFilter');
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
            | Add Writers - File writer should be available on local server
            |--------------------------------------------------------------------------
            */
            $logger->addWriter('file')->filter('priority@notIn', array());
            return $logger;
        };
    }
}