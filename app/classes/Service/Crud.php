<?php

namespace Service;

use Obullo\Database\Pdo\QueryBuilder,
    Obullo\Database\Pdo\Handler\Mysql;

/**
 * Crud Database Service ( Shared )
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
        $c['crud'] = function ($database) use ($c) {
            return new QueryBuilder($c, $database);
        };
    }
}

// END Crud class

/* End of file Crud.php */
/* Location: .classes/Service/Crud.php */