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
 * @license   http://opensource.org/licenses/MIT
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
                        'host'     => $c['config']['database'][$key]['host'],
                        'username' => $c['config']['database'][$key]['username'],
                        'password' => $c['config']['database'][$key]['password'],
                        'database' => $c['config']['database'][$key]['database'],
                        'port'     => $c['config']['database'][$key]['port'],
                        'charset'  => $c['config']['database'][$key]['charset'],
                    )
                )
            );
        };
    }
}

// END Crud class

/* End of file Crud.php */
/* Location: .classes/Service/Crud.php */