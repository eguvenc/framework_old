<?php

namespace Service;

use Obullo\Database\Pdo\QueryBuilder;

/**
 * Query Builder
 *
 * @category  Service
 * @package   Database
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/services
 */
Class Query implements ServiceInterface
{
    /**
     * Configure servide parameters
     * 
     * @param object $c container
     */
    public function __construct($c)
    {
        $c['config']['query.params.database'] = array('db' => 'db', 'provider' => 'mysql');  // set provider parameters
    }

    /**
     * Registry
     *
     * @param object $c container
     * 
     * @return void
     */
    public function register($c)
    {
        $c['query'] = function () use ($c) {
            return new QueryBuilder($c);
        };
    }
}

// END Crud class

/* End of file Crud.php */
/* Location: .classes/Service/Crud.php */
