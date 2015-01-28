<?php

namespace Service;

use Obullo\Container\Container,
    Obullo\ServiceProvider\ServiceInterface,
    Obullo\Database\Pdo\Handler\Mysql;

/**
 * Db Service ( Shared )
 *
 * @category  Service
 * @package   Db
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/services
 */
Class Db implements ServiceInterface
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
        $c['db'] = function () use ($c) {
            return $c->load('service provider database', ['connection' => 'default']);
        };
    }
}

// END Db class

/* End of file Db.php */
/* Location: .classes/Service/Db.php */