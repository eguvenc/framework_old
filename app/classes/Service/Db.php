<?php

namespace Service;

use Obullo\Database\Pdo\Handler\Mysql;

/**
 * Db Service
 *
 * @category  Service
 * @package   Db
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/service
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
    public function register($c)
    {
        $c['db'] = $c->alias(
            'db', 
            function ($params = array('db' => 'db')) use ($c) {
                return new Mysql(
                    $c,
                    $params
                );
            }
        );
    }
}

// END Db class

/* End of file Db.php */
/* Location: .classes/Service/Db.php */