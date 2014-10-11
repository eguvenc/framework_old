<?php

namespace Service;

use Obullo\Database\Crud\Crud as ActiveRecord;

/**
 * Crud Database Service
 *
 * @category  Service
 * @package   Database
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/services
 */
Class Crud implements ServiceInterface
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
        $c['crud'] = function () use ($c) {
            return new ActiveRecord(
                new Mysql(
                    $c,
                    array(
                        'host'     => $c->load('config')['database'][$key]['host'],
                        'username' => $c->load('config')['database'][$key]['username'],
                        'password' => $c->load('config')['database'][$key]['password'],
                        'database' => $c->load('config')['database'][$key]['database'],
                        'port'     => $c->load('config')['database'][$key]['port'],
                        'charset'  => $c->load('config')['database'][$key]['charset'],
                    )
                )
            );
        };
    }
}

// END Crud class

/* End of file Crud.php */
/* Location: .classes/Service/Crud.php */