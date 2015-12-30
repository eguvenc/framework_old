<?php

namespace Log;

use Obullo\Container\ContainerInterface as Container;
use Obullo\Container\ServiceInterface;

use Obullo\Log\Logger;
use Obullo\Log\NullLogger;

/**
 * Log Service Manager
 * 
 * @copyright 2009-2016 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
class LogManager implements ServiceInterface
{
    /**
     * Container class
     * 
     * @var object
     */
    protected $c;

    /**
     * Constructor
     * 
     * @param ContainerInterface $container container
     */
    public function __construct(Container $container)
    {
        $this->c = $container;
    }

    /**
     * Set service parameters
     * 
     * @param array $params service configuration
     *
     * @return void
     */
    public function setParams(array $params)
    {
        if ($this->c['config']['log']['enabled']) {
            $this->c['logger.params'] = $params;
        }
    }

    /**
     * Register
     * 
     * @return object logger
     */
    public function register()
    {
        $this->c['logger'] = function () {

            if (! $this->c['config']['log']['enabled']) {
                return new NullLogger;
            }
            return new Logger($this->c, $this->c['logger.params']);
        };
    }

}