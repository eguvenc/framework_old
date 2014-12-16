<?php
/*
|--------------------------------------------------------------------------
| Rbac Configuration Constants
|--------------------------------------------------------------------------
| Configuration of base constants
|
*/

/**
 * RBAC roles table definitions
 */
define('RBAC_ROLES_DB_TABLENAME', 'rbac_roles');
define('RBAC_ROLES_COLUMN_PRIMARY_KEY', 'rbac_role_id');
define('RBAC_ROLES_COLUMN_PARENT_ID', 'rbac_role_parent_id');
define('RBAC_ROLES_COLUMN_TEXT', 'rbac_role_name');
define('RBAC_ROLES_COLUMN_TYPE', 'rbac_role_type');
define('RBAC_ROLES_COLUMN_LEFT', 'rbac_role_lft');
define('RBAC_ROLES_COLUMN_RIGHT', 'rbac_role_rgt');

/**
 * RBAC user table definitions
 */
define('RBAC_USER_DB_TABLENAME', 'rbac_user');


/**
 * RBAC permissions table definitions
 */
define('RBAC_PERM_DB_TABLENAME', 'rbac_permissions');
