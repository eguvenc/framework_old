## Models <a name="models"></a>

Bye bye to traditional models which kill productivity of developers we create models <b>on the fly</b> using <b>Schemas</b>.

**Note:** In other words we use models in the controller section. The reading operations are <b>optional</b> .

**Note:** Look at <kbd>Odm</kbd> docs for more details.

### What is a Model? <a name="what-is-a-model"></a>

------

Models are PHP classes that are designed to work with information in your database. 

### Model Reference

```php
new Model(string $var, mixed $schemaOrTable = '', string $dbVar = 'db');
```

* <b>First Parameter:</b> Specifies the controller variable, you can access it like $this->var->method();
* <b>Second Parameter:</b> Sets database tablename or schema array, if you provide an array it will convert to schema object.
* <b>Third Parameter:</b> Sets the current database variable, default is "db".


### Creating & Loading Models

```php
new Model('user', 'users');
```
This code create a model on the fly and store it into <b>$this->user</b> variable. All models are empty classes and they extend to Odm Class automatically.

### Creating a Schema

Schema is a simply class that contains your <b>labels</b>, <b>data types</b> and <b>validaton rules</b>. A schema class is located in your <kbd>schemas</kbd> folder and looks like below the example.

```php
<?php
namespace Schema;

Class Users
{
    public $_colprefix = 'user_';

    public $id;
    public $email = array('label' => 'User Email', 'rules' => 'required|_string(60)|validEmail');
    public $password = array('label' => 'User Password', 'rules' => 'required|_string(255)|minLen(6)');
}

/* End of file users.php */
/* Location: .public/schemas/users.php */
```

##### Directory Structure

```php
+ app
    - schemas
        user.php
    + tasks
+ classes
+ packages
```

### Creating Schemas Automatically ( Only Mysql )

When you <b>call a model</b>, <kbd>model package</kbd> creates automatically the schema file if it does not exist. 
Using your tablename the <kbd>schema_mysql</kbd> <b>package</b> parses your column information of your database table and it builds automatically the current validation rules.

If you provide the schema object in array format schema driver also will create the database table if its not exists.

**Note:** At this time we have just <b>Mysql Schema Driver</b>. if you want a write schema driver for other database types, please search on the net how to <b>submit a package</b> to Obullo.


### Using Array Schema

```php
$userSchema['users'] = array(
            'email'    => array('label' => 'User Email', 'rules' => 'required|_string(60)|validEmail'),
            'password' => array('label' => 'User Password', 'rules' => 'required|_string(255)|minLen(6)')
        );

new Model('user', $userSchema);
```

### Creating Model Functions

After loading the model you need to build your model functions.

Use <b>$this->model->func();</b> method to build model functions.

```
<?php
$this->user->func('save',function() {
        return $this->db->insert('users', $this);
});
?>
```

Then you can call your method.

```
<?php
$this->user->save();
?>
```

This is same for other crud operations.

```
<?php
$this->user->func('delete',function() {
        $this->db->where('id', 5);
        return $this->db->delete('users');
});
```

```
<?php
$this->user->delete();
?>
```

Available <b>CRUD operations</b> that we recommend are listed below. You can define any of these methods.

* save
* insert
* update
* replace
* delete
* remove
* read


**Note:** Look at <kbd>Odm Package</kbd> docs for more details.