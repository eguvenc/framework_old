## Validation in Model (Vmodel Class)<a name="validation-in-model"></a>

### What is the Validation in Model

Obullo has a Vmodel model class who want to do validation in model instead of controller. Validation model class use [Validator Class](/ob/validator/releases/0.0.1) to form validations. Using Validation Model you can create Native or <b>Ajax</b> forms easily.

If you want to use Vmodel Class simply extend your model to it.

```php
<?php Class User extends Odm
{
    function __construct()
    {
        parent::__construct();
    }
    
    public $settings = array(
    'database' => 'db',
    'table'    => 'users',
    'primary_key' => 'usr_id',
    'fields' => array
     (
        'usr_id' => array(
          'label' => 'ID',
          'type'  => 'int',
          'rules' => 'trim|integer'
        ),
        'usr_username' => array(
         'label'  => 'Username',  // you can use lang:username
         'type'   => 'string',
         'rules'  => 'required|trim|unique|min_len[3]|max_len[100]|xss_clean'
        ),
        'usr_password' => array(
          'label' => 'Password',
          'type'  => 'string',
          'rules' => 'required|trim|min_lenght[6]|encrypt',
          'func'  => 'md5'
        ),
        'usr_confirm_password' => array(
          'label' => 'Confirm Password',
          'type'  => 'string',
          'rules' => 'required|encrypt|matches[usr_password]'
        ),
        'usr_email' => array(
          'label' => 'Email Address',
          'type'  => 'string',
          'rules' => 'required|trim|valid_email'
        )
    ));
}
```

As you can see above the example we just put validation rules public <var>$settings</var> variable then we save the class in /models directory.

Load the User Model from your controller and see the action

```php
$user = new Model\User(false);  // Include user model
$user->usr_username = 'blabla';    // Set string or a post/get variable
$user->usr_password = i\getPost('usr_password');
$user->usr_email    = i\getPost('usr_email');

if($user->save())
{
     echo 'Saved !'
} 
else
{
     print_r($user->errors());
}
```

### Validation In Model Tutorial

------

Download the Obullo Framework and you will find the a real Validation Model Tutorial in the <dfn>modules/welcome</dfn> folder then just run this url.

```php
http://localhost/framework/index.php/welcome/start
```

### Function Reference

------

#### $model->errors($field = '');

This function return to all errors in array format if you don't provide any fieldname, otherwise it will return to <b>one field</b> error.

```php
print_r($model->errors());

Array
(
    [usr_username] => The Username field is required.
    [usr_password] => The Password field is required.
    [usr_confirm_password] => The Confirm Password field is required.
    [usr_email] => The Email Address field is required.
    [success] => 0
)
```

If save function run successfully then the <b>[success]</b> array value will be <b>1</b> (true) otherwise it will return to <b>0</b> (false).

### Getting Transactional Errors

------

```php
if($model->errors('transaction') != '')
{
    echo $model->errors('transaction');
}
```

#### $model->setError($field = '', $message = '');

You can set custom errors.

#### $model->setField($field = '', $type = 'rules', $val = ' new value ');

Using set field function you can override to $settings or validation rules.

```php
$user = new Model\User(false);
            
// override to rule of  usr_username.
$model->setField('usr_username', 'rules', 'trim|required');

$model->where('usr_username',  'someusername');
            
if($model->delete())
{               
     echo form_Json\success();

     return;
}
```

#### $model->values($field = '');

Function return to <b>filtered secure</b> value after that the validation according to your variable <b>type</b> of your validation rules in your model <var>$settings</var> .

```php
print_r($model->values());

Array
(
    [usr_id] => 0
    [usr_username] => 'blabla'
    [usr_password] => 
    [usr_confirm_password] => 
    [usr_email] => 
)
```

#### $model->save();

Function will save the all variables to database which are the fields matches in your model class $settings => fields section.

```php
$user->usr_username = 'blabla';    // Set string or a post/get variable
$user->usr_password = i\getPost('usr_password');
$user->save();
```

Function will return to <b>TRUE</b> if success, otherwise it will return to <b>FALSE</b>.

### Multiple Save and Validation

------

Just use your model in your foreach function.

```php
$user = new Model\User(false);

$users[] = array('usr_username' => 'test', 'usr_email' => 'me@me.com');
$users[] = array('usr_username' => 'test2', 'usr_email' => 'example@example.com');

$errors = array();
foreach($users as $key => $val)
{
     $user = new Model_User();
     $user->usr_username = $val['usr_username'];
     $user->usr_email = $val['usr_email'];

     if( ! $user->save())
     {
           $errors[] = $user->errors();
     }
}

print_r($errors);
```

#### $model->delete();

Function will delete the all variables to database which are the fields matches in your model class $settings => fields section.

```php
$user->where('usr_id', 5);   // you can use post/get variable .. e.g. i_post('usr_id')
$user->where('usr_username', 'username');
$user->delete();
```

Delete providing by fieldname

```php
$user = new Model\User(false);  // Include user model


$user->where('usr_id', 5);

if($user->delete())
{
    echo 'User Deleted Successfuly !';
}

print_r($user->errors());
```

Delete providing by array

```php
$user = new Model\User(false);  // Include user model


$user->whereIn('usr_id', array('1', '4', '5'))

if($user->delete())
{
    echo 'User Deleted Successfuly !';
}

print_r($user->errors());
```

You can define your <b>Custom Save</b> or <b>Custom Delete</b> function in your model class, just remove the <b>parent::save();</b> method and put your own functions.

```php
function save()
{   
    $result = parent::save();

    return $result;
}
```

Functions will return to <b>TRUE</b> if success, otherwise they will return to <b>FALSE</b>.

#### $model->validation();

Function will return FALSE if $model validation success.

#### $model->validate($fields = array());

If you don't want to save fields to database, just want to validation you can use the validation requests function, if you provide any array data fields, function validate just them, otherwise function validate the all fields.

```php
$member = new Model\Member(false);


$member_form  = $member->validate(array('usr_agreement'));

if($member_form)
{
     Everythings ok !
}
```

#### $model->noSave($field);

Some times we don't want to save some fields or that the fields which we haven't got in the db tables we have to validate them.Overcome to this problem we use $model->no_save(); function.

```php
$member = new Model\Member(false);

$model->usr_first_lastname   = i_get_post('usr_first_lastname');
$model->usr_password         = i_get_post('usr_password');

$member->noSave('usr_agreement'); // we don't have these fields in the db table.
$member->noSave('usr_password_confirm');

if($member->save())
{
    echo 'Saved New Nember !';
}
```

#### $model->item($var);

Fetch items from your model <var>$settings</var> variable.

```php
echo $model->item('primary_key');  // usr_id

print_r($model->item("fields[usr_id]"));  // Array ( [label] => ID [type] => int [rules] => trim|integer )
```

#### parent::validate($fields);

Using a parent validate function you can validate the items when you work inside the model.

```php
Class User extends Odm
{
    function __construct()
    {
        parent::__construct();
    }
    
    public $settings = array(
    'database' => 'db',
    'table'    => 'users',
    'primary_key' => 'usr_id',
    'fields' => array
     (
        'usr_id' => array(
          'label' => 'ID',
          'type'  => 'int',
          'rules' => 'trim|integer'
        )
    ));
    
    function get($limit = '', $offset = '', $id = '')
    {        
        $this->db->select('*');
        
        if($id != '')
        {
            $data = array('id' => $id);
            parent::validate($data);  // manually validate ID field
        }
        
        return $this->db->get('users', $limit, $offset);
    }
}
```

#### $this->beforeSave();

Creating a before save function in your model, you can control the saving extra jobs <b>before</b> the saving data to current table. For example you can keep the user logs into database using it.

```php
function beforeSave()
{
    $data['usr_ip'] = i_ip_address();

    $this->db->insert('usr_logs', $data);
}
```

#### $this->afterSave();

Creating a after save function in your model, you can control the saving extra jobs <b>after</b> the saving data to current table. For example you can keep the saved data as log into database using it.

```php
function afterSave()
{ 
    $array = array();
    foreach($this->settings['fields'] as $key => $val)
    {
          $array[$key]  = $this->values($key);
    }

    $data['saved_data'] = seralize($array);

    $this->db->insert('save_logs', $data);
}
```

#### $this->buildQueryErrors();

Build Httpd GET friendly errors using query strings.

```php
echo $model->buildQueryErrors() // output: errors[user_password]=Wrong%20Password!&errors['user_email']=Wrong%20Email%20Address!
```

### Rule Reference

------

The following is a list of all the native rules that are available to use:

<table><thead><tr>
<th>Rule</th><th>Parameter</th><th>Description</th><th>Example</th></tr>
<tr><td>required</td><td>No</td><td>Returns FALSE if the form element is empty.</td><td> 	
<tr><td>matches</td><td>Yes</td><td>Returns FALSE if the form element does not match the one in the parameter.</td><td>matches[form_item]</td></tr>
<tr><td>min_len</td><td>Yes</td><td>Returns FALSE if the form element is shorter then the parameter value.</td><td>min_len[6]</td></tr>
<tr><td>max_len</td><td>Yes</td><td>Returns FALSE if the form element is longer then the parameter value.</td><td>max_len[12]</td></tr>
<tr><td>exact_len</td><td>Yes</td><td>Returns FALSE if the form element is not exactly the parameter value.</td><td>exact_len[8]</td></tr>
<tr><td>alpha</td><td>No</td><td>Returns FALSE if the form element contains anything other than alphabetical characters.</td><td></td></tr> 	
<tr><td>alpha_numeric</td><td>No</td><td>Returns FALSE if the form element contains anything other than alpha-numeric characters.</td><td></td></tr> 	
<tr><td>alpha_dash</td><td>No</td><td>Returns FALSE if the form element contains anything other than alpha-numeric characters, underscores or dashes.</td><td></td></tr> 	
<tr><td>numeric</td><td>No</td><td>Returns FALSE if the form element contains anything other than numeric characters.</td><td></td></tr>	
<tr><td>integer</td><td>No</td><td>Returns FALSE if the form element contains anything other than an integer.</td><td></td></tr> 	
<tr><td>is_natural</td><td>No</td><td>Returns FALSE if the form element contains anything other than a natural number: 0, 1, 2, 3, etc.</td><td></td></tr> 	
<tr><td>is_natural_no_zero</td><td>No</td><td>Returns FALSE if the form element contains anything other than a natural number, but not zero: 1, 2, 3, etc.</td><td></td></tr> 	
<tr><td>valid_email</td><td>No</td><td>Returns FALSE if the form element does not contain a valid email address.</td><td></td></tr> 	
<tr><td><kbd>valid_email_dns</kbd></td><td>No</td><td>Returns FALSE if the form element does not contain a valid email AND dns query return to FALSE.</td><td></td></tr>	
<tr><td><kbd>valid_emails</kbd></td><td>Yes</td><td>Returns FALSE if any value provided in a comma separated list is not a valid email. (If parameter TRUE or 1 function also will do a dns query foreach emails)</td><td>valid_emails[true]</td></tr>
<tr><td>valid_ip</td><td>No</td><td>Returns FALSE if the supplied IP is not valid.</td><td>	
<tr><td>valid_base64</td><td>No</td><td>Returns FALSE if the supplied string contains anything other than valid Base64 characters.</td><td></td></tr>	
<tr><td><kbd>no_space</kbd></td><td>No</td><td>Returns FALSE if the supplied string contains space characters.</td><td></td></tr> 	
<tr><td>callback_function[param]</td><td>Yes</td><td>You can define a custom callback function which is a class method located in your current model or just a function.</td><td>callback_functionname[param]</td></tr>
<tr><td><kbd>callback_request[method][request_uri]</kbd></td><td>Yes</td><td>Returns TRUE if the supplied hmvc request response == 1 or response == 'TRUE' otherwise returns FALSE .</td><td>calback_request[post][/captcha/check/]</td></tr>
<tr><td><kbd>valid_date</kbd></td><td>Yes</td><td>Returns FALSE if the supplied date is not valid in current format.</td><td>Enter your date format default is mm-dd-yyyy.</td></tr></tbody></table>

### Prepping Reference

------

The following is a list of all the prepping functions that are available to use:

<table><thead>
<th>Name</th><th>Parameter</th><th>Description</th></thead><tbody>
<tr><td>xss_clean</td><td>No</td><td>Runs the data through the XSS filtering function, described in the [Security Helper](/docs/helpers/security-helper) page.</td></tr>
<tr><td>prep_for_form</td><td>No</td><td>Converts special characters so that HTML data can be shown in a form field without breaking it.</td></tr>
<tr><td>prep_url</td><td>No</td><td>Adds "http://" to URLs if missing.</td></tr>
<tr><td>strip_image_tags</td><td>No</td><td>Strips the HTML from image tags leaving the raw URL.</td></tr>
<tr><td>encode_php_tags</td><td>No</td><td>Converts PHP tags to entities.</td></tr></tbody></table>

#### $model->debug();

Returns latest sql query if your environment not set to LIVE.

```php
echo $model->debug();

// UPDATE users  SET username = 'x'  WHERE user_id = 5;
```

### Ajax Form Plugin Rules

------

If you use ajax requests you need to [Form Send Helper](/docs/packages/#form-send-helper) to encode Validaton Model Response in Json format. If you use Obullo Jquery Form Plugin the following is a list of all the Form plugin functions that are available to use

you can control the form plugin functions using the class <b>attribute</b>

```php
<? echo formOpen('/welcome/start/do_post.json', array('method' => 'POST', 'class' => 'hide-form no-top-msg'));?>
```

### Ajax Form Plugin Attributes

------

<table><thead><tr>
<th>Attribute</th><th>Description</th></tr><tbody>
<tr><td>no-top-msg</td><td>Form plugin default show a top message warn to user that you can disable this functionality using as class attribute in your form.</td></tr>
<tr><td>no-ajax</td><td>If you disable to ajax post you can use no-ajax class attribute, when the user click to submit button form will do a native post request instead of ajax post.</td></tr>
<tr><td>hide-form</td><td>If the form successfully posted with no errors you can hide the form area and you can show the users just success message.</td></tr><</tbody></table>

### Using AJAX - Vmodel and Form Send Helper

------

After that load the <b>Obullo JQuery Form Plugin</b> just create a do_post() function in your controller then you need add a <samp>form_open('module/controller/do_post.json');</samp> code in your view file, remember we have a ajax form tutorial in modules/test folder. You can look at there for more details.

```php
function doPost() 
{
    $user = new Model\User(false);  // Include user model
    loader::helper('ob/form_send');

    $user->usr_username = i\getPost('usr_username');
    $user->usr_password = i\getPost('usr_password');
    $user->usr_email    = i\getPost('usr_email');

    if($user->save())
    {
            echo form_Json\success($model);
            return;
    } 
    else
    {
            echo form_Json\error($user);
            return;
    }
}
```

### Sending Custom Messages

------

```php
function doPost() 
{
    $user = new Model\User(false);  // Include user model
    loader::helper('ob/form_send'); new Form\Send

    
    $user->usr_username = i\getPost('usr_username');
    $user->usr_password = i\getPost('usr_password');
    $user->usr_email    = i\getPost('usr_email');

    if($user->save())
    {
            $user->setError('msg', 'Data Saved Successfully !');

            echo form_Json\success($user);
            return;
    } 
    else
    {
            echo form_Json\error($user);
            return;
    }
}
```

### Ajax Debugging

------

You can see the latest sql query sending it to json.

```php
if($user->save())
{
    echo form_Json\success($model);
    return;
} 
else
{
    echo form_Json\error($this->db->last_query());
    return;
}
```

### Transaction Support

------

Vmodel Library automatically support the transactions if your table engine setted correctly as INNODB. If you want to learn more details about transactions look at [database transactions](/docs/database/database-transactions) section.

## Auto-loading and Auto-running <a name="auto-loading"></a>

### Autoloader Functions

------

Obullo has a <b>autoload.php</b> functionality that is located in your <dfn>app/config</dfn> folder.

When your application start to work, if would you like to load automatically some obullo or application <samp>( models, helpers, languages, views )</samp> files for whole framework , you should define filenames to $autoload variable.

### Autoloading Helpers

------

Autoloading files loading path styles same as loader class.

```php
$autoload['helper']  = array('vi', 'html');
```

### Autoloading Libraries

------

Autoloading files loading path styles same as loader class.

```php
$autoload['library'] = array('calendar', 'classes/myLib', );
```

### Autoloading Locale Files

------

Same as helper files.

### Autoloading Config Files

------

Same as helper files.

### Autoloading Models

------

Same as library files.

### Autorun Functions

------

Obullo has a <b>autorun.php</b> functionality that is located in your <dfn>app/config</dfn> folder.

When your application start to work, if would you like to run automatically some autoloaded <b>helper</b> functions for whole framework , you should define function names and arguments to $autorun variable.

```php
$autorun['function']['sess\start'] = array();  This configuration run the Obullo sess_start(); function.
```

You can use arguments

```php
$autorun['function']['my\function']   = array('arg1', 'arg2');
```

Above the configuration run this function <samp>my\function('arg1', 'arg2');</samp> before if you load the function helper.

## Common Functions <a name="common-functions"></a>

### Common Functions

------

Obullo uses a few functions for its operation that are globally defined, and are available to you at any point. These do not require loading any libraries or helpers.

#### getInstance()

This function returns the Obullo super object. Normally from within your controller functions you will call any of the available Obullo functions using the <var>$this</var> variable. <var>$this</var>, however, only works directly within your controllers, <b>not</b> models, and <b>not</b> your views. If you would like to use Obullo's classes from within your own custom classes , models or views you can do so as follows:

First, assign the Obullo object to a variable:

```php
$ob = getInstance();
```

Once you've assigned the object to a variable, you'll use that variable instead of <var>$this</var>:

```php
$ob = getInstance();

$ob->config->item('base_url');

 // or 
$ob->output->cache(60);

// etc. 
```

*Tip:* Don't forget <samp>this() = $this</samp> if <var>$this</var> variable not available in anywhere .

*Note:* For Model files <var>$this</var> variable just available for <b>database</b> operations.To using libraries inside to model you must use <samp>this()</samp> function.

#### getConfig($config_filename, $variable = '')

getConfig is a pretty function to getting configuration variables from <dfn>app/config</dfn> folder. You can use it like this

```php
$config = getConfig('config');  print_r($config); 
  
//  output
Array ( [display_errors] => 1 [timezone_set] => Europe/Istanbul [base_url] => http://localhost/obullo/ ...
```

If the config variable name in the file is not same via filename then you can grab it like this

```php
$conf = getConfig('myconfig', 'conf'); print_r($conf);
```

**Note:** You can't grab diffrerent multiple variables in one config file via *get_config()* function. One variable must be per file.

**Tip:** If you have multiple config variables in a config file you can use associative arrays <b>$your_conf_var = array( 'setting1' => '', 'setting2' => '');</b>

First parameter is filename and the second is <b>$variable</b> name you want to fetch.

#### configItem('item_key', $filename = '')

The [Config library](/docs/core-libraries/config-class) is the preferred way of accessing configuration information, however configItem() can be used to retrieve single keys. See Config library documentation for more information.

```php
echo configItem('base_url'); //output http://example.com  
```

If you want to get config item another folder use it like this

```php
echo configItem('html4-trans', 'doctypes'); 
```

#### dbItem('item_key', index = '')

Get db configuration items which is defined in <dfn>app/config/database.php</dfn> file.

Grab current database settings

```php
echo dbItem('hostname');   //output localhost  
```
Grab another database settings

```php
echo dbItem('hostname', 'db2');   //output localhost  
```

#### isReallyWritable('path/to/file')

isWritable() returns TRUE on Windows servers when you really can't write to the file as the OS reports to PHP as FALSE only if the read-only attribute is marked. This function determines if a file is actually writable by attempting to write to it first. Generally only recommended on platforms where this information may be unreliable.

```php
if (isReallyWritable('file.txt'))
{
    echo "I could write to this if I wanted to";
}
else
{
    echo "File is not writable";
}
```

#### showError('message'), show_404('page'), logMe('level', 'message')

These are each outlined on the [Error Handling](#) page.

#### setStatusHeader(code, 'text');

Permits you to manually set a server status header. Example:

```php
setStatusHeader(401);
// Sets the header as:  Unauthorized
```
[See here](http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html) for a full list of headers.

lib();

------

Load the obullo libraries

```php
$email = lib('ob/email');
```
 
## Sh Commands<a name="sh-commands"></a>

If you work under the *nix operating systems, we have useful sh commands.

#### Clear.sh ( Clear logs and cache files )

When you move your project to another server you need to clear all log files and caches. Go your terminal and type your project path and run the clear.sh

```php
root@localhost:/var/www/framework$ sh clear.sh // Great, temporary files clear job done !
```

#### Export.sh ( Export project files )

When you upload project files to your live server you need export it. Export command remove all .svn and .git files and save the project to export folder.

```php
root@localhost:/var/www/framework$ sh export.sh  // Export process named as export_2012-11-12_13-19 and completed !
```

## HMVC<a name="hmvc"></a>

### What is the HMVC ?

------

#### Hierarchical-Model-View-Controller (HMVC)

The HMVC pattern decomposes the client tier into a hierarchy of parent-child MVC layers. The repetitive application of this pattern allows for a structured client-tier architecture. Obullo has a simple HMVC library and Obullo's simple HMVC library support <b>internal requests</b> at this time.

```php
MVC    
                  _____________
                 |             |     
                 | Controller  |
                 |_____________|
                /              \
 _____________ /                \ ______________
|             |                  |              |
|   Model     | ---------------- |    View      |        
|_____________|                  |______________|
```

```php
HMVC (Layered MVC)

        ------  
        | c  | -------
        ------        \
       /      \        \
------        -------   \
| m  |        |  v  |    \
------        -------   ------
                        | c  | ------   
                        ------        \
                       /      \        \
                ------        -------   \
                | m  |        |  v  |    \
                ------        -------   ------
                                        | c  |          
                                        ------
                                       /      \
                                ------        -------
                                | m  |        |  v  |
                                ------        -------    
```

HMVC pattern offers more flexibility in your application. You can call multiple requests using Obullo's [Request helper](/docs/packages/#request-helper).

```php
------------------------------------------------------------------------------------
|                                                                                   |
|          echo request('home/start/header')->exec();                               |
|                                                                                   |
|                                                                                   |                                   
|-----------------------------------------------------------------------------------|
|                                                                                   |
|                                                                                   |
| echo request('news/start/sports/tennis')->exec();                                 |
|                                                                                   |
|                                                                                   |                                   
|                                                                                   |
|                                                                                   |
| echo request('news/start/economy/exchange')->exec();                              |
|                                                                                   |
|                                                                                   |
|                                                                                   |
-------------------------------------------------------------------------------------
|                                                                                   |
|  $footer = request('home/start/footer', $params =array() , $cache = 1000);        |
|                                                                                   |
|             echo $footer->exec();                                                 |     
|                                                                                   |
-------------------------------------------------------------------------------------
```

Another example, we created a <b>captcha</b> module using hmvc methods download the latest version of Obullo and look at the captcha module. And just an idea you can create a login service creating a <b>login</b> folder then you can do request from your controllers using HMVC. Learn more details about Obullo's HMVC library click [Reuest helper](/docs/helpers/request-helper) link.

## URI Routing<a name="uri-routing"></a>

### URI Routing

------

Typically there is a one-to-one relationship between a URL string and its corresponding /module/controller class/method. The segments in a URI normally follow this pattern:

```php
example.com/module/class/function/id/
```

In some instances, however, you may want to remap this relationship so that a different class/function can be called instead of the one corresponding to the URL.

```php
example.com/index.php/shop/show/product/1
```

For example, lets say you want your URLs to have this prototype:

```php
example.com/shop/product/1/
example.com/shop/product/2/
example.com/shop/product/3/
example.com/shop/product/4/
```

Normally the second segment of the URL is reserved for the class name (show), but in the example above it 
instead has a product. To overcome this, Obullo allows you to remap the URI handler.

### Setting your APPLICATION routing rules

------

Routing rules are defined in your <var>app/config/routes.php</var> file. In it you'll see an array called $routes that permits you to specify your own routing criteria. Routes can either be specified using <dfn>wildcards</dfn> or <dfn>Regular Expressions</dfn>

Setting your MODULE routing rules

------

Sub module available uri routing example
example.com/submodule/module/class/function/id/

You can create routing rules for the current module just you need to create a routes.php in your module /config directory. You must do an array called $routes that permits you to specify your own routing criteria for the module.

Important:If you want to define routing rules for a module your module name and routing rules first segment must be same , otherwise router library can't parse your route settings .Look at the example.
$routes['modulename'] = 'modulename/users/login';   
Setting your SUBMODULE / MODULE routing rules

You can create routing rules your routes.php in your module /config directory which is located in your SUBMODULE.
$routes['backend/(:any)'] = 'welcome/index/$1';    You don't need write again submodule name to routing values the route rule which is located in a submodule.
Wildcards

A typical wildcard route might look something like this:
$routes['shop/product/:num'] = "shop/show/product";

In a route, the array key contains the URI to be matched, while the array value contains the destination it should be re-routed to. In the above example, if the literal word "shop" is found in the first segment of the URL, and "product" is found in the second segment and a number is found in the third segment then "shop" directory and the "product" class are instead used.

You can match literal values or you can use two wildcard types:
:num
:any

:num will match a segment containing only numbers.
:any will match a segment containing any character.

Note: Routes will run in the order they are defined. Higher routes will always take precedence over lower ones.
Examples

Here are a few routing examples:
$routes['blog'] = "blog/start";

A URL containing the word "blog" in the first segment will be remapped to the "blog" directory and "start" class.
$routes['blog/users'] = "blogs/start/users/34";

A URL containing the segments blog/users will be remapped to the "blogs" directory , "start" class and the "users" method. The ID will be set to "34".
$routes['shop/product/:any'] = "shop/show/product";

A URL with "shop/product" as the first segment, and anything in the second will be remapped to the "show" class and the "product" method.
$routes['product/(:num)'] = "shop/show/product/$1";

A URL with "product" as the first segment, and anything in the second will be remapped to the "shop" directory, "show" class and the "product" method passing in the match as a variable to the function.
We can tell the process via schema like this // This is your url request           // This process will work in background
example.com/index.php/product/4
          _ _ _ _ _ _ _                   _ _ _ _ _ _ _ _
                |                                |
                |                                |
// This is your url mask               // This is your process value
$routes['product/(:num)']         =   "shop/show/product/$1";
     
                |                                 |
                |_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _|
                
                                |
                                |
                        // process in background 
                       "shop/show/product/4";      

Important: Do not use leading/trailing slashes.
Regular Expressions

If you prefer you can use regular expressions to define your routing rules. Any valid regular expression is allowed, as are back-references.

Note: If you use back-references you must use the dollar syntax rather than the double backslash syntax.

A typical RegEx route might look something like this:
$routes['products/([a-z]+)/(\d+)'] = "$1/id_$2";

In the above example, a URI similar to shop/products/shirts/123 would instead call the shirts controller class and the id_123 function.

You can also mix and match wildcards with regular expressions.
A Real Example
When we was prepare the Obullo User Guide pages we had some long urls like this http://obullo.com/user_guide/show/page/4/29/uri-routing.html
http://obullo.com/user_guide/show/chapter/1/base-information.html
http://obullo.com/user_guide/show.html

As you can see above the example we had a user_guide directory and show class, we decided to remove "show" segment from uri and we did it like this ..
$routes['user_guide/page/(:num)/(:num)/:any']  = "user_guide/show/page/$1/$2";
$routes['user_guide/chapter/(:num)/:any']      = "user_guide/show/chapter/$1";
$routes['user_guide/chapters']  = "user_guide/show"; and result http://obullo.com/user_guide/page/4/29/uri-routing.html
http://obullo.com/user_guide/chapter/1/base-information.html
http://obullo.com/user_guide/chapters.html
Reserved Routes

There is a one reserved route:
$routes['default_controller'] = 'welcome/start';

This route indicates which controller class should be loaded if the URI contains no data, which will be the case when people load your root URL. In the above example, the "start" class would be loaded. You are encouraged to always have a default route otherwise a 404 page will appear by default.

**Important:** The reserved routes must come before any wildcard or regular expression routes.



