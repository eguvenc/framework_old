## Acl Class

-------

Acl Class provides a lightweight and simple access control list (ACL) implementation for privileges management.

### Initializing the Class

-------

```php
new Acl();
$this->acl->method();
```

Once loaded, the Acl object will be available using: <dfn>$this->acl->method();</dfn>

### Grabbing the Instance

------

Also using new Acl(false); boolean you can grab the instance of Obullo libraries,"$this->acl->method()" will not available in the controller.

```php
$acl = new Acl(false);
$acl->method();
```

### Creating a Group ( Roles ) and Adding Access

------

```php
$acl = new Acl(false);

$acl->addGroup('@admin');
$acl->allow('@admin', 'create_user');

$acl->addMember('obullo', '@admin');

if($acl->isAllowed('obullo', 'delete_user'))
{
     echo 'Obullo has a create user access !';
}
```

### Creating a Group and Deleting Access

-------

```php
$acl = new Acl(false);

$acl->addGroup('@admin');
$acl->addGroup('@editor');

$acl->allow('@admin', array('create_user', 'delete_user'));
$acl->allow('@editor', array('create_user', 'delete_user'));

$acl->deny('@editor', 'delete_user');

$acl->addMember('obullo', '@admin');
$acl->addMember('john', '@editor');

if(  ! $acl->isAllowed('john', 'delete_user'))
{
     echo 'John hasn't got delete user access !';
}
```

### Check group access

------

```php
$acl = new Acl(false);

$acl->addGroup('@admin');
$acl->allow('@admin', array('create_user', 'delete_user'));

if(  $acl->isAllowed('@admin', 'delete_user'))
{
     echo 'Great @admin group has got delete user access !';
}
```

### Function Reference

------

#### $acl->addGroup('@groupname')

Creates a group ( role ), the group name must be have "@" prefix e.g. @admin.

#### $acl->allow('@groupname', mixed 'operation')

Add operation access to access list for provided group.

#### $acl->deny('@groupname', mixed 'operation')

Delete operation access to access list for provided group.

#### $acl->addMember('membername', '@groupname')

Add member to provided group.

#### $acl->delMember('membername', '@groupname')

Delete member from provided group.

#### $acl->isAllowed('member_or_@group, 'operation_name')

Check user or group has access for provided operation.

