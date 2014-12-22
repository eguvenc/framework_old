<?php

namespace Service\Provider;

use Obullo\Database\Crud\Query;

/**
 * Crud Database Provider
 *
 * @category  Provider
 * @package   Database
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/providers
 */
Class Crud implements ProviderInterface
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
        $c['provider:crud'] = function ($params = array('db' => 'db')) use ($c) {
            return new Query($c->load('return service/provider/database', $params['db']));  // Replace database object with crud.
        };
    }
}

// END Crud class

/* End of file Crud.php */
/* Location: .classes/Service/Provider/Crud.php */