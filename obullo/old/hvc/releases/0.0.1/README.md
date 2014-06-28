
## Hvc Class

Using hvc package, you can execute hvc requests between your controllers. 

## What is Hvc ? ( Hierarchical-View–Controller ) ( No model )

Hvc requests compatible with Ajax Requests and also has a caching functionality. The "Hvc Design Pattern" thoroughly used in the Framework.
Each Hvc uri creates a random connection string. And each random Uri able to do Memory Cache if you provide expiration time as third paramater.

Some information about Hierarchical Controllers [Hierarchical Controllers in Java World](http://www.javaworld.com/article/2076128/design-patterns/hmvc--the-layered-pattern-for-developing-strong-client-tiers.html)

##### A Hvc request creates the random connection string (hvc Key) as the following steps.

*  The request method gets the uri and serialized string of your data parameters
*  then it builds <b>md5 hash</b>
*  finally add it to the end of your hvc uri.
*  in this technique hvc <b>key</b> used for <b>caching</b> mechanism.

Example Cache Usage

```php
$this->hvc->get('private/comments/getuser', array('user_id' => 5), $expiration = 7200);
```

Example Output ( Private Controller )

```php
<?php

/**
 * $c get/getuser
 * 
 * @var Private Controller
 */
$c = new Controller(
    function () {
	   new Get;
	   new Db;
    }
);

$c->func(
    'index',
    function () {
    	$this->db->where('id', $this->get->get('user_id'));
    	$this->db->get('users');
        $r = array(
            'results' => $this->db->getResultArray(),
            'message' => $this->uri->getUriString(),
            //output // private/comments/getuser/hvc_key_6eff3bbc8c8c7ba883be5da437c43f56
        );
        echo json_encode($r);
    }
);

/* End of file getuser.php */
/* Location: ./private/comments/controller/getuser.php */
```

<b>Note:</b> You can find more information about <b>hvc term</b> in the docs Advanced Topics / HVC section.

### Initializing the Class

------

```php
new Hvc;
$this->hvc->method();
```

Here is a very simple example showing you how to call an hvc request.

```php              
echo $this->hvc->get('folder/controller/method', $data = array(), $expiration = 0);
```

Example Delete

```php
<?php

/**
 * $c create
 * 
 * @var Public Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Form;
        new Sess;
        new Auth;
        new Hvc;
    }
);

$c->func(
    'index',
    function($id){
        $r = $this->hvc->post('private/posts/delete/'.$id, 
            array('user_id' => $this->auth->getIdentity('user_id'))
        );
        $this->form->setNotice($r['message'], $r['success']); // set flash notice
        $this->url->redirect('/post/manage');
    }
);
```

**Note:**  Use <b>"{ }"</b> braces to remove the <b>"/index"</b> method. Otherwise you have to write full uri e.g. <b>private/posts/delete/index/$id</b>.

### Function Reference

------

#### $this->hvc->post($uri, $data = array | int, expiration = 0);  

This method do a post request to provided uri ( same as CRUD <b>Create</b> ).
The third parameter is memory cache expiration, if you send it as a <b>"> 0"</b> it will send output to your <b>memory driver</b>.

**Note:** You can use the <b>second paramater</b> as <b>expiration (int) time</b> if you don't send the data variable.

#### $this->hvc->get($uri, $data = array | int, expiration = 0);

This method do a get request to provided uri ( same as CRUD <b>Read</b> ).
The third parameter is memory cache expiration if you send it as a "0" it will remove the current.

**Note:** You can use the <b>second paramater</b> as <b>expiration (int) time</b> if you don't send the data variable.

#### $this->hvc->put($uri, $data = string | array | int, expiration = 0);

This method do a put request to provided uri ( same as CRUD <b>Update</b> ). When you use PUT method also you can send your $data parameter as a string.

#### $this->hvc->delete($uri, $data = array | int, expiration = 0);

This method do a delete request to provided uri ( same as CRUD <b>Delete</b> ).

#### $this->hvc->setServer(string $key, string $val);

Sets the $_SERVER headers for current hvc scope.

#### $this->hvc->getKey();

Returns to random hvc key using serialized string of the data which are builded your method parameters.

#### $this->hvc->deleteCache($key = null);

If $key not provided hvc will delete the cache using current key.

