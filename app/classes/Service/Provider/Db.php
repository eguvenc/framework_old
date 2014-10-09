<?php

namespace Service\Provider;

use Obullo\Database\Pdo\Mysql;

/**
 * Db Provider
 *
 * @category  Provider
 * @package   Db
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/providers
 */
Class Db implements ProviderInterface
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
        $c['provider:db'] = $c->alias(
            'db', 
            function ($key = 'db') use ($c) {
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
            }
        );
    }
}

// END Db class

/* End of file Db.php */
/* Location: .classes/Service/Provider/Db.php */