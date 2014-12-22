<?php

namespace Service;

use Obullo\Database\Crud\Query;

/**
 * Crud Database Service
 *
 * @category  Service
 * @package   Database
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
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
        $c['crud'] = function ($params = array('db' => 'db')) use ($c) {
            
            return new Query(
                new Mysql(
                    $c,
                    array(
                        'host'     => $c['config']['database'][$params['db']]['host'],
                        'username' => $c['config']['database'][$params['db']]['username'],
                        'password' => $c['config']['database'][$params['db']]['password'],
                        'database' => $c['config']['database'][$params['db']]['database'],
                        'port'     => $c['config']['database'][$params['db']]['port'],
                        'charset'  => $c['config']['database'][$params['db']]['charset'],
                    )
                )
            );
        };
    }
}

// END Crud class

/* End of file Crud.php */
/* Location: .classes/Service/Crud.php */