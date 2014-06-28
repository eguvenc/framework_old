## Acl Class

-------

Acl Class provides a lightweight and simple access control list (ACL) implementation for privileges management.

### Initializing the Class

-------

```php
new Acl();
$this->acl->method();
```

Once loaded, the Acl object will be available using: <kbd>$this->acl->method();</kbd>

### Creating a Group ( Roles ) and Adding Access

------

```php
$this->acl->addGroup('@admin');
$this->acl->allow('@admin', 'create_user');
$this->acl->addMember('maestro', '@admin');

if($this->acl->isAllowed('maestro', 'delete_user'))
{
     echo 'Maestro has a create user access !';
}
```

### Creating a Group and Deleting Access

-------

```php
$this->acl->clear();
$this->acl->addGroup('@admin');
$this->acl->addGroup('@editor');

$this->acl->allow('@admin', array('create_user', 'delete_user'));
$this->acl->allow('@editor', array('create_user', 'delete_user'));

$this->acl->deny('@editor', 'delete_user');

$this->acl->addMember('obullo', '@admin');
$this->acl->addMember('john', '@editor');

if(  ! $this->acl->isAllowed('john', 'delete_user'))
{
     echo 'John hasn't got delete user access !';
}
```

### Checking Group Access

------

```php
$this->acl->addGroup('@admin');
$this->acl->allow('@admin', array('create_user', 'delete_user'));

if(  $this->acl->isAllowed('@admin', 'delete_user'))
{
     echo 'Great @admin group has got delete user access !';
}
```

### Function Reference

------

#### $this->acl->addGroup('@groupname')

Creates a group ( role ), the group name must have the prefix "@"  e.g. @admin.

#### $this->acl->allow('@groupname', mixed 'operation')

Adds operation access to access list for provided group.

#### $this->acl->deny('@groupname', mixed 'operation')

Deletes operation access to access list for provided group.

#### $this->acl->addMember('membername', '@groupname')

Adds member to provided group.

#### $this->acl->delMember('membername', '@groupname')

Deletes the member from the provided group.

#### $this->acl->isAllowed('member_or_@group, 'operation_name')

Checks if a user or group has access for provided operation.

#### $this->acl->clear()

Clears / Resets all the class variables.
