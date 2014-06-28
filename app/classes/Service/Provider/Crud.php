<?php

namespace Service\Provider;

use Obullo\Database\Crud\Crud as ObulloCrud;

/**
 * Crud Database Provider
 *
 * @category  Provider
 * @package   Database
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
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
        $c['provider:crud'] = function ($key = 'db') use ($c) {
            return new ObulloCrud($c->load('return service/provider/database', $key));  // Replace database object with crud.
        };
    }
}

// END Database class

/* End of file Database.php */
/* Location: .classes/Service/Provider/Database.php */