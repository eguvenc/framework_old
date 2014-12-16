<?php
/*
|--------------------------------------------------------------------------
| Rbac Config
|--------------------------------------------------------------------------
| Configuration of database tables
|
*/
return array(

    'database' => array(
        'columns' => array(

            'roles' => array(
                'db.tablename'   => 'rbac_roles',
                'db.primary_key' => 'rbac_role_id',
                'db.parent_id'   => 'rbac_role_parent_id',
                'db.text'        => 'rbac_role_name',
                'db.type'        => 'rbac_role_type',
                'db.left'        => 'rbac_role_lft',
                'db.right'       => 'rbac_role_rgt',
            ),
            'user_roles' => array(
                'db.tablename'        => 'rbac_user_roles',
                'db.user_primary_key' => 'users_user_id',
                'db.role_primary_key' => 'rbac_roles_rbac_role_id',
                'db.assignment_date'  => 'rbac_user_role_assignment_date',
            ),
            'operations' => array(
                'db.tablename'   => 'rbac_operations',
                'db.primary_key' => 'rbac_operation_id',
                'db.text'        => 'rbac_operation_name',

            ),
            'permissions' => array(
                'db.tablename'   => 'rbac_permissions',
                'db.primary_key' => 'rbac_permission_id',
                'db.parent_id'   => 'rbac_permission_parent_id',
                'db.text'        => 'rbac_permission_name',
                'db.resource'    => 'rbac_permission_resource',
                'db.type'        => 'rbac_permission_type',
                'db.left'        => 'rbac_permission_lft',
                'db.right'       => 'rbac_permission_rgt',
            ),
            'role_permissions' => array(
                'db.tablename'        => 'rbac_role_permissions',
                'db.role_primary_key' => 'rbac_roles_rbac_role_id',
                'db.perm_primary_key' => 'rbac_permissions_rbac_permission_id',
                'db.assignment_date'  => 'rbac_role_permission_assignment_date',

            ),
            'op_permissions' => array(
                'db.tablename'        => 'rbac_op_permissions',
                'db.perm_primary_key' => 'rbac_permissions_rbac_permission_id',
                'db.op_primary_key'   => 'rbac_operations_rbac_operation_id',
                'db.role_primary_key' => 'rbac_roles_rbac_role_id',
            ),
            'db.allow' => 'allow',
            'db.deny'  => 'deny'
        )
    )

);

/* End of file rbac.php */
/* Location: ./app/config/rbac.php */