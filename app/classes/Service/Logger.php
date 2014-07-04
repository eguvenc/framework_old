<?php

namespace Service;

use Obullo\Log\Disabled, 
    Obullo\Log\Handler\File,
    Obullo\Log\Handler\Mongo,
    Obullo\Log\Logger as ObulloLogger;

/**
 * Log Provider
 *
 * @category  Provider
 * @package   Mongo
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/providers
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
            $logger = new ObulloLogger($c, $c->load('config')['log'], $c->load('service/queue'));

            $logger->addWriter(
                LOGGER_FILE,
                function () use ($c, $logger) { 
                    return new File($c, $logger);  // primary
                },                                                // must be available working on local server.
                3  // priority
            );

            $logger->addHandler(
                LOGGER_MONGO, 
                function () use ($c, $logger) { 
                    return new Mongo(
                        $c,
                        $logger, 
                        array(
                        'dsn' => 'mongodb://root:12345@localhost:27017/test', 
                        'collection' => 'test_logs'
                        )
                    );
                },
                1  // priority
            );

            /*
            |--------------------------------------------------------------------------
            | Removes file handler and use second defined handler as primary 
            | in "production" ( live ) mode.
            |--------------------------------------------------------------------------
            */
            if (ENV == 'prod') {
                $logger->removeWriter(LOGGER_FILE);
                // $logger->addWriter(); your production log writer
            }
            return $logger;
        };
    }
}

// END Logger class

/* End of file Logger.php */
/* Location: .classes/Service/Provider/Logger.php */