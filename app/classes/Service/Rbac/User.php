<?php

namespace Service\Rbac;

use Service\ServiceInterface;
use Obullo\Permissions\Rbac\User as ObulloUser;

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
Class User implements ServiceInterface
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
        $c['rbac/user'] = function () use ($c) {
            return new ObulloUser(
                $c,
                array(
                    'db.tablename'              => 'rbac_user_roles',
                    'db.user_id'                => 'user_id',
                    'db.role_id'                => 'role_id',
                    'db.rbac_op_tablename'      => 'rbac_operations',
                    'db.rbac_op_perm_tablename' => 'rbac_op_permissions',
                    'db.op_id'                  => 'op_id',
                    'db.op_text'                => 'operation',
                    'db.roles_tablename'        => 'rbac_roles',
                    'db.roles_text'             => 'role_name',
                    'db.perm_type'              => 'perm_type',
                    'db.role_perm_tablename'    => 'rbac_role_permissions',
                    'db.user_roles_tablename'   => 'rbac_user_roles',
                    'db.perm_id'                => 'perm_id',
                    'db.perm_text'              => 'perm_name',
                    'db.perm_resource'          => 'perm_resource',
                    'db.perm_tablename'         => 'rbac_permissions',
                    'db.assignment_date'        => 'assignment_date',
                    'db.allow'                  => 'allow',
                    'db.deny'                   => 'deny',
                )
            );
        };
    }
}

// END Cache class

/* End of file Cache.php */
/* Location: .classes/Service/Cache.php */