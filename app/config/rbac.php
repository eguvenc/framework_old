<?php

/**
 * RBAC "roles" table definitions
 */
define('RBAC_ROLES_DB_TABLENAME', 'rbac_roles');
define('RBAC_ROLES_COLUMN_PRIMARY_KEY', 'rbac_role_id');
define('RBAC_ROLES_COLUMN_PARENT_ID', 'rbac_role_parent_id');
define('RBAC_ROLES_COLUMN_TEXT', 'rbac_role_name');
define('RBAC_ROLES_COLUMN_TYPE', 'rbac_role_type');
define('RBAC_ROLES_COLUMN_LEFT', 'rbac_role_lft');
define('RBAC_ROLES_COLUMN_RIGHT', 'rbac_role_rgt');

/**
 * RBAC "user_roles" table definitions
 */
define('RBAC_USER_ROLES_DB_TABLENAME', 'rbac_user_roles');
define('RBAC_USER_ROLES_TABLE_USER_PRIMARY_KEY', 'users_user_id');
define('RBAC_USER_ROLES_TABLE_ROLE_PRIMARY_KEY', 'rbac_roles_rbac_role_id');
define('RBAC_USER_ROLES_COLUMN_ASSIGNMENT_DATE', 'rbac_user_role_assignment_date');

/**
 * RBAC "permissions" table definitions
 */
define('RBAC_PERM_DB_TABLENAME', 'rbac_permissions');
define('RBAC_PERM_COLUMN_PRIMARY_KEY', 'rbac_permission_id');
define('RBAC_PERM_COLUMN_PARENT_ID', 'rbac_permission_parent_id');
define('RBAC_PERM_COLUMN_TEXT', 'rbac_permission_name');
define('RBAC_PERM_COLUMN_RESOURCE', 'rbac_permission_resource');
define('RBAC_PERM_COLUMN_TYPE', 'rbac_permission_type');
define('RBAC_PERM_COLUMN_LEFT', 'rbac_permission_lft');
define('RBAC_PERM_COLUMN_RIGHT', 'rbac_permission_rgt');

/**
 * RBAC "role_permissions" table definitions
 */
define('RBAC_ROLE_PERM_DB_TABLENAME', 'rbac_role_permissions');
define('RBAC_ROLE_PERM_TABLE_ROLES_PRIMARY_KEY', 'rbac_roles_rbac_role_id');
define('RBAC_ROLE_PERM_TABLE_PERM_PRIMARY_KEY', 'rbac_permissions_rbac_permission_id');
define('RBAC_ROLE_PERM_COLUMN_ASSIGNMENT_DATE', 'rbac_role_permission_assignment_date');

/**
 * RBAC "operations" table definitions
 */
define('RBAC_OPERATIONS_DB_TABLENAME', 'rbac_operations');
define('RBAC_OPERATIONS_COLUMN_PRIMARY_KEY', 'rbac_operation_id');
define('RBAC_OPERATIONS_COLUMN_TEXT', 'rbac_operation_name');

/**
 * RBAC "op_permissions" table definitions
 */
define('RBAC_OP_PERM_DB_TABLENAME', 'rbac_op_permissions');
define('RBAC_OP_PERM_TABLE_PERM_PRIMARY_KEY', 'rbac_permissions_rbac_permission_id');
define('RBAC_OP_PERM_TABLE_OP_PRIMARY_KEY', 'rbac_operations_rbac_operation_id');
define('RBAC_OP_PERM_TABLE_ROLE_PRIMARY_KEY', 'rbac_roles_rbac_role_id');