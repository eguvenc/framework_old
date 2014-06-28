<?php

namespace Service\Rbac;

use Service\ServiceInterface;
use Obullo\Permissions\Rbac\Permissions as ObulloPermissions;

/**
 * Permission Service
 *
 * @category  Service
 * @package   Cache
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/container
 */
Class Perms implements ServiceInterface
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
        $c['rbac/perms'] = function () use ($c) {
            return new ObulloPermissions(
                $c,
                array(
                    'db.perm_tablename'      => 'rbac_permissions',
                    'db.role_perm_tablename' => 'rbac_role_permissions',
                    'db.primary_key'         => 'perm_id',
                    'db.role_id'             => 'role_id',
                    'db.perm_type'           => 'perm_type',
                    'db.parent_id'           => 'parent_id',
                    'db.text'                => 'perm_name',
                    'db.resource'            => 'perm_resource',
                    'db.assignment_date'     => 'assignment_date',
                    'db.left'                => 'lft',
                    'db.right'               => 'rgt',
                )
            );
        };
    }
}

// END Perms class

/* End of file Perms.php */
/* Location: .classes/Service/Perms.php */