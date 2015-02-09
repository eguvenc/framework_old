<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\ServiceProviders\ServiceInterface;
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
class Query implements ServiceInterface
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
        $c['query'] = function () use ($c) {
            return new QueryBuilder($c, ['connection' => 'default']);
        };
    }
}

// END Query class

/* End of file Query.php */
/* Location: .classes/Service/Query.php */
