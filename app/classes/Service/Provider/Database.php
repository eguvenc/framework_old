<?php

namespace Service\Provider;

use Obullo\Database\Pdo\Mysql;

/**
 * Database Provider
 *
 * @category  Provider
 * @package   Database
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/providers
 */
Class Database implements ProviderInterface
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
        $c['provider:database'] = function ($key = 'db') use ($c) {
                return new Mysql(
                    $c,
                    array(
                        'host'     => $c->load('config')['database'][$key]['host'],
                        'username' => $c->load('config')['database'][$key]['username'],
                        'password' => $c->load('config')['database'][$key]['password'],
                        'database' => $c->load('config')['database'][$key]['database'],
                        'port'     => $c->load('config')['database'][$key]['port'],
                        'charset'  => $c->load('config')['database'][$key]['charset'],
                    )
                );
        };
    }
}

// END Database class

/* End of file Database.php */
/* Location: .classes/Service/Provider/Database.php */