<?php

namespace Service\Rbac;

use Service\ServiceInterface;
use Obullo\Permissions\Rbac\Roles as ObulloRoles;

/**
 * Cache Service
 *
 * @category  Service
 * @package   Cache
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/container
 */
Class Roles implements ServiceInterface
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
        $c['rbac/roles'] = function () use ($c) {
            return new ObulloRoles(
                $c,
                array(
                    'db.tablename'   => 'rbac_roles',
                    'db.primary_key' => 'role_id',
                    'db.parent_id'   => 'parent_id',
                    'db.text'        => 'role_name',
                    'db.type'        => 'role_type',
                    'db.left'        => 'lft',
                    'db.right'       => 'rgt',
                )
            );
        };
    }
}

// END Cache class

/* End of file Cache.php */
/* Location: .classes/Service/Cache.php */