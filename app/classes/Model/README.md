
## Models

Obullo is <b>"no model"</b> it don't offer you a model structure but if you want to use your own model you can create them in <kbd>app/classes/Model</kbd> folder. 

### Initializing the Models

-------

```php
<?php
$c->load('model/user');
$this->modelUser->method();
```

#### Using "As" Command

```php
<?php
$c->load('model/user as user');
$this->user->method();
```

#### Using "New" Command

```php
<?php
$c->load('new model/user');
$this->user->method();
```

#### Using "Return" Command

```php
<?php
$user = $c->load('return model/user');
$user->method();
```

#### Using Parameters

```php
<?php
$c->load('model/user', array('param' => 1));
$this->user->method();
```

#### Creating Model Class

```php
<?php

namespace Model;

/**
 * User Model
 * 
 * @category  Models
 * @package   User
 * @author    Your <yourname@youremail.com>
 * @copyright 2009-2014 Your name
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://yourapplication.com
 */
Class User
{
	/**
	 * Container
	 * 
	 * @var object
	 */
	public $c;

	/**
	 * Constructor
	 * 
	 * @param object $c container
	 */
	public function __construct($c)
	{
		$this->c = $c;
	}

	public function insert()
	{
		// sql query
	}

	public function delete()
	{
		// sql query
	}
}

// END User model class

/* End of file User.php */
/* Location: .app/classes/Model/User.php */
```

#### Example Model Usage

```
<?php

/**
 * $app hello_world
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('view');
        $c->load('model/user as user');
        $this->user->method();
    }
);

$app->func(
    'index', 
    function () {


    }
);

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */
```