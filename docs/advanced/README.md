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

```php
example.com/submodule/module/class/function/id/
```

You can create routing rules for the current module just you need to create a <var>routes.php</var> in your module <dfn>/config</dfn> directory. You must do an array called <dfn>$routes</dfn> that permits you to specify your own routing criteria for the module.

**Important:**If you want to define routing rules for a module your module name and routing rules first segment must be same , otherwise router library can't parse your route settings .Look at the example.

```php
$routes['modulename'] = 'modulename/users/login';   
```

Setting your SUBMODULE / MODULE routing rules

------

You can create routing rules your <var>routes.php</var> in your module <dfn>/config</dfn> directory which is located in your <b>SUBMODULE</b>.

```php
$routes['backend/(:any)'] = 'welcome/index/$1';    
```

You <b>don't need write again submodule name</b> to routing values the route rule which is located in a submodule.

### Wildcards

-------

A typical wildcard route might look something like this:

```php
$routes['shop/product/:num'] = "shop/show/product";
```

In a route, the array key contains the URI to be matched, while the array value contains the destination it should be re-routed to. In the above example, if the literal word "shop" is found in the <dfn>first</dfn> segment of the URL, and "product" is found in the <dfn>second</dfn> segment and a number is found in the <dfn>third</dfn> segment then "shop" directory and the "product" class are instead used.

You can match literal values or you can use two wildcard types:

```php
:num
:any
```
<b>:num</b> will match a segment containing only numbers.
<b>:any</b> will match a segment containing any character.

**Note:** Routes will run in the order they are defined. Higher routes will always take precedence over lower ones.

Examples

------

Here are a few routing examples:

```php
$routes['blog'] = "blog/start";
```

A URL containing the word "blog" in the first segment will be remapped to the "blog" directory and "start" class.

```php
$routes['blog/users'] = "blogs/start/users/34";
```

A URL containing the segments blog/users will be remapped to the "blogs" directory , "start" class and the "users" method. The ID will be set to "34".

```php
$routes['shop/product/:any'] = "shop/show/product";
```
A URL with "shop/product" as the first segment, and anything in the second will be remapped to the "show" class and the "product" method.

```php
$routes['product/(:num)'] = "shop/show/product/$1";
```

A URL with "product" as the first segment, and anything in the second will be remapped to the "shop" directory, "show" class and the "product" method passing in the match as a variable to the function.
We can tell the process via schema like this 

```php
// This is your url request           // This process will work in background
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
```

**Important:** Do not use leading/trailing slashes.

### Regular Expressions

------

If you prefer you can use regular expressions to define your routing rules. Any valid regular expression is allowed, as are back-references.

**Note:** If you use back-references you must use the dollar syntax rather than the double backslash syntax.

A typical RegEx route might look something like this:

```php
$routes['products/([a-z]+)/(\d+)'] = "$1/id_$2";
```

In the above example, a URI similar to <dfn>shop/products/shirts/123</dfn> would instead call the <dfn>shirts</dfn> controller class and the <dfn>id_123</dfn> function.

You can also mix and match wildcards with regular expressions.

### A Real Example

------

When we was prepare the Obullo User Guide pages we had some <b>long urls</b> like this 

```php
http://obullo.com/user_guide/show/page/4/29/uri-routing.html
http://obullo.com/user_guide/show/chapter/1/base-information.html
http://obullo.com/user_guide/show.html
```

As you can see above the example we had a <b>user_guide</b> directory and show class, we decided to <b>remove "show"</b> segment from uri and we did it like this ..

```php
$routes['user_guide/page/(:num)/(:num)/:any']  = "user_guide/show/page/$1/$2";
$routes['user_guide/chapter/(:num)/:any']      = "user_guide/show/chapter/$1";
$routes['user_guide/chapters']  = "user_guide/show";
```

and result 

```php
http://obullo.com/user_guide/page/4/29/uri-routing.html
http://obullo.com/user_guide/chapter/1/base-information.html
http://obullo.com/user_guide/chapters.html
```

### Reserved Routes

------

There is a one reserved route:

```php
$routes['default_controller'] = 'welcome/start';
```

This route indicates which controller class should be loaded if the URI contains no data, which will be the case when people load your root URL. In the above example, the "start" class would be loaded. You are encouraged to always have a default route otherwise a 404 page will appear by default.

**Important:** The reserved routes must come before any wildcard or regular expression routes.

##Tasks and CLI Requests<a name="tasks-and-cli-requests"></a> 

Obullo has a integrated task functionality and Command Line Interface support who want to create command line tasks. You can run <b>controllers</b> from command line.

### CLI (Command Line Interface) Requests

------

To run task controller which is located in app/task folder just type below the codes in your Terminal. Below the example we use <samp>/var/www</samp> path this is your webroot and it depends on your configuration.

First of all go your framework root folder.

```php
$cd /var/www/framework/
```

Do a request, all command line requests goes to Obullo <b>task.php</b> which is located in your root directory.

```php
$php task.php hello
```

This request call the <samp>hello</samp> controller from <b>tasks</b> folder which is located in your /<samp>app/tasks</samp> directory.

```php
        _____      ________     __     __  __        __          _______
      / ___  /    / ____   \   / /    / / / /       / /         / ___   /
    /  /   /  /  / /____/  /  / /    / / / /       / /        /  /   /  /
   /  /   /  /  / _____  /   / /    / / / /       / /        /  /   /  /
  /  /___/  /  / /____/  \  / /____/ / / /____   / /_____   /  /__ /  /
  /_______/   /__________/ /________/ /_______/ /_______ /  /_______/ 
  
                Welcome to Obullo Task Manager (c) 2011.
     Please run this command [$php task.php hello help] for help ! 
                YOU ARE IN /APP/TASKS FOLDER
```

If you see this screen your command successfully run <b>otherwise</b> check your <b>php path</b> running by this command

```php
$which php // command output /usr/bin/php 
```

If your current php path not <b>/usr/bin/php</b> open the index.php which is located in your framework root and define your php path like this 

```php
define('PHP_PATH', 'your_php_path_that_you_learned_by_which_command'); 
```

### Running Module Tasks

------

Running below the code will call the another task controller from <dfn>/MODULES/tasks</dfn> directory.

```php
$php task.php start
```

Running below the code will call the another task controller from your <dfn>/MODULES/welcome/tasks</dfn> directory.

```php
$php task.php welcome start index arg1 arg2
```

If above the command successful you will see this screen

```php
Module: welcome
Hello World !
Argument 1: arg1
Argument 2: arg2
The Start Controller Index function successfully works !
```

**Note:** When you use the CLI operations *cookies* and *sessions* will not works as normal. Please use the tasks for advanced Command Line structures.

Tasks

------

Tasks are same thing like controller model view structure. The <b>main difference from CLI</b> in the <b>task mode</b> framework rules works like browsing a web page <b>sessions</b> and another some things works well in this mode.To working with tasks just we call the controller from the command line and we have special directories for this operation called tasks.

Obullo has <b>three</b> tasks folder called APPLICATON, MODULES and YOUR MODULE TASKS folder.

#### APPLICATION TASK FOLDER

```php
- app
    + config
    + core
    + helpers
    + libraries
    + models
    + parents
    - tasks
       - controllers
           hello.php


#### MODULES TASK FOLDER

```php
+ app
- modules
   + captcha
   + default
   - tasks
       - controllers
            start.php

#### YOUR MODULE TASK FOLDER

```php
+ app
- modules
   + captcha
   + default
   - welcome
      - tasks
        - start
           start.php
```

### Running APPLICATION Tasks

------

All command line request goes to Obullo <b>task.php</b> which is located in your root directory. Obullo has a <b>/tasks</b> folder in the application directory if you want to create application tasks just create your controllers, models, helpers .. files in this folder.

Using [Task Helper](/docs/helpers/#task-helper) you can run your tasks as a command in the background. Function run like CLI but don't use the task.php.

```php
new Task\start();

Task\run('hello help', $debug = TRUE);
```

Running MODULE Tasks

------

```php
new Task\start();

Task\run('start help', $debug = TRUE);
```

### Running YOUR MODULE Tasks

------

```php
new Task\start();

Task\run('welcome start/index/arg1/arg2', $debug = TRUE);
```

<b>An Important thing</b> we use the second argument <b>$debug = true</b> just for test don't use this argument except the testing or use it as false.

```php
Task\run('welcome hello/index/arg1/arg2', FALSE);
```

**Note:** When you use the task function, debug mode should be <samp>FALSE</samp>, or don't use a second argument except the test otherwise shell command will print output the screen.

## Alternate PHP Syntax for View Files <a name="alternate-php-syntax"></a>

### Alternate PHP Syntax for View Files

------

If you do not utilize Obullo's [template engine](#), you'll be using pure PHP in your View files. To minimize the PHP code in these files, and to make it easier to identify the code blocks it is recommended that you use PHPs alternative syntax for control structures and short tag echo statements. If you are not familiar with this syntax, it allows you to eliminate the braces from your code, and eliminate "echo" statements.

Short Tag Support

------

**Note:** Obullo does not support php short tags at this time. You can open *'short_tag_open'* value from your php.ini file.If you use shared hosting don't use short tags for now.

**Important:** If your server does not support *short_tag_open* functionality then all your php codes will shown as string which is used short tags.

Alternative Echos

------

Normally to echo, or print out a variable you would do this:

```php
<?php echo $variable; ?>
```

With the alternative syntax you can instead do it this way:

```php
<?=$variable?>
```

### Alternative Control Structures

------

Controls structures, like <var>if</var>, <var>for</var>, <var>foreach</var>, and <var>while</var> can be written in a simplified format as well. Here is an example using foreach:

```php
<ul>

<?php foreach($todo as $item): ?>

<li><?=$item?></li>

<?php endforeach; ?>

</ul>
```

Notice that there are no braces. Instead, the end brace is replaced with endforeach. Each of the control structures listed above has a similar closing syntax: <var>endif</var>, <var>endfor</var>, <var>endforeach</var>, and <var>endwhile</var>

Also notice that instead of using a semicolon after each structure (except the last one), there is a colon. This is important!


Here is another example, using if/elseif/else. Notice the colons:

```php
<?php if ($username == 'sally'): ?>

   <h3>Hi Sally</h3>

<?php elseif ($username == 'joe'): ?>

   <h3>Hi Joe</h3>

<?php else: ?>

   <h3>Hi unknown user</h3>

<?php endif; ?>
```

## Error Handling and Debugging<a name="error-handling-and-debugging"></a>

### Error Handling

------

Error Codes and Error Constants

```php
http://usphp.com/manual/en/errorfunc.constants.php
 
1     E_ERROR
2     E_WARNING
4     E_PARSE
8     E_NOTICE
16    E_CORE_ERROR
32    E_CORE_WARNING
64    E_COMPILE_ERROR
128   E_COMPILE_WARNING
256   E_USER_ERROR
512   E_USER_WARNING
1024  E_USER_NOTICE
2048  E_STRICT
4096  E_RECOVERABLE_ERROR
8192  E_DEPRECATED
16384 E_USER_DEPRECATED
30719 E_ALL
```

Obullo lets you build error reporting into your applications using the functions described below. In addition, it has an error logging class that permits error and debugging messages to be saved as text files.

**Note:** By default, Obullo displays all PHP errors. You might wish to change this behavior once your development is complete. Disabling error reporting will NOT prevent log files from being written if there are errors.

### Enable / Disable Errors

------

Using your <dfn>application/config/config.php</dfn> you can control the all application errors.

```php
$config['error_reporting']       = 1;
```

this configuration will enable all errors you can use PHP ERROR CONSTANTS like this

```php
$config['error_reporting']       = 'E_ALL';
```

this configuration will disable all errors.

```php
$config['error_reporting']       = 0;
```

You can do more using php error constants. Look at below the examples.

```php
|   String - Custom Regex Mode Examples:
|
|   Running errors
|       $config['error_reporting'] = 'E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR';
|   
|   Running errors + notices
|       $config['error_reporting'] = 'E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_NOTICE';
|   
|   All errors except notices, warnings, exceptions and database errors
|       $config['error_reporting'] = 'E_ALL ^ (E_NOTICE | E_WARNING | E_EXCEPTION | E_DATABASE)';
|       
|   All errors except notices 
|       $config['error_reporting'] = 'E_ALL ^ E_NOTICE';
```

Unlike most systems in Obullo, the error functions are simple procedural interfaces that are available globally throughout the application. This approach permits error messages to get triggered without having to worry about class/function scoping.

#### error\reporting()

------

Sometimes you may want to disable framework <b>error reporting</b> functionality to some line of codes, in procedural way php allows to you closing error reporting manualy using '@' symbol.

But this functionality will not work in Obullo becasue of Obullo use its own <b>debugging functionality</b> and it disables native error functionality. However Obullo has ob_error_reporting(); function and it will do nearly same effect.

```php
error\reporting(0);   // Close error reporting. 

$fp = fopen('filename.txt', 'wb');    // error reporting closed for this function. 

error\reporting(1);  // Restore old value of error reporting. 
```

The following functions let you generate errors:

#### showError('message' [, int $status_code= 500 ] )

------

This function will display the error message supplied to it using the following error template:

You can <b>customize</b> this template which is located at <samp>app/core/errors/ob_general.php</samp>

The optional parameter $status_code determines what HTTP status code should be sent with the error.

#### show404('page')

------

This function will display the 404 error message supplied to it using the following error template:

You can <b>customize</b> this template which is located at <samp>app/core/errors/ob_404.php</samp>

The function expects the string passed to it to be the file path to the page that isn't found. Note that Obullo automatically shows 404 messages if controllers are not found.

#### log\me('level', 'message')

------

This function lets you write messages to your log files. You must supply one of three "levels" in the first parameter, indicating what type of message it is (debug, error, info), with the message itself in the second parameter. Example:

```php
if ($some_var == "")
{
    log\me('error', 'Some variable did not contain a value.');
}
else
{
    log\me('debug', 'Some variable was correctly set');
}

log\me('info', 'The purpose of some variable is to provide some value.');
```

There are three message types:
<ol>
   <li>Error Messages. These are actual errors, such as PHP errors or user errors.</li>
    <li>Debug Messages. These are messages that assist in debugging. For example, if a class has been initialized, you could log this as debugging info.</li>
    <li>Informational Messages. These are the lowest priority messages, simply giving information regarding some process. Obullo doesn't natively generate any info messages but you may want to in your application.</li></ol>

**Note:** In order for the log file to actually be written, the "logs" folder must be writable which is located at <dfn>app/core/logs</dfn>. In addition, you must set the "threshold" for logging. You might, for example, only want error messages to be logged, and not the other two types. If you set it to zero logging will be disabled. (Look at <dfn>app/config/config.php</dfn>)

#### Log\me('level', '[ module ]: message')

------

If you want to keep releated module log files in your current module or extension, create a <dfn>module/core/logs</dfn> folder and give the write access it, then you need to use <b>'[ module ] : '</b> string before the start of the log message. For example if you have a <b>welcome</b> module you need to use log function like this.

```php
Log\me('debug', '[ welcome ]: Example message !');
```

#### Exceptions

------

We catch all exceptions with php <samp>set_exception_handler()</samp> function.You can customize exception error template which is located <dfn>app/core/errors/ob_exception.php</dfn>.

```php
<div id="exception_content">
<b>(<?php echo $type ?>):  <?php echo error\securePath($e->getMessage(), true) ?></b><br/>
<?php 
if(isset($sql)) 
{
    echo '<span class="errorfile"><b>SQL :</b> '.$sql.'</span>';
}
?>
</div>
```

**Tip:**You can manually catch your special exceptions in try {} catch {} blocks like this...

```php
try
{
    throw new Exception('blabla');
    
} catch(Exception $e)
{
    echo $e->getMessage();  //output blabla 
}
```

### Debugging

------

Obullo lets you build user friendly debugging into your applications using the configurations described below. Open your <dfn>app/config/config.php</dfn> and look at the <var>$config['debug_backtrace']</var>

```php
$config['debug_backtrace']  = array('enabled' => 'E_ALL ^ (E_NOTICE | E_WARNING)', 'padding' => 5);
```

You can enable or disable debugging functionality or you can disable it using native php error regex strings.

```php
$config['debug_backtrace']  = array('enabled' => FALSE, 'padding' => 5)
```

If you change the padding option debugging lines will be smaller.

```php
$config['debug_backtrace']  = array('enabled' => 'E_ALL ^ (E_NOTICE | E_WARNING)', 'padding' => 3); (
```

```php
Notice): Undefined variable: undefined 
MODULES/welcome/controllers/welcome.php Code : 8 ( Line : 14 )

12     {   
13 
14         echo $undefined;
15         
16         $data['var'] = 'and generated by ';
17 
```

## Caching and Compression<a name="caching-compression"></a>

### Web Page Caching

------

Obullo lets you cache your pages in order to achieve maximum performance.

Although Obullo is quite fast, the amount of dynamic information you display in your pages will correlate directly to the server resources, memory, and processing cycles utilized, which affect your page load speeds. By caching your pages, since they are saved in their fully rendered state, you can achieve performance that nears that of static web pages.

### How Does Caching Work?

------

Caching can be enabled on a per-page basis, and you can set the length of time that a page should remain cached before being refreshed. When a page is loaded for the first time, the cache file will be written to your application/system/cache folder. On subsequent page loads the cache file will be retrieved and sent to the requesting user's browser. If it has expired, it will be deleted and refreshed before being sent to the browser.

Note: The Benchmark tag is not cached so you can still view your page load speed when caching is enabled.

### Enabling Caching

------

To enable caching, put the following tag in any of your controller functions:

```php
$this->output->cache(n);
```

Where <var>n</var> is the number of <b>minutes</b> you wish the page to remain cached between refreshes.

The above tag can go anywhere within a function. It is not affected by the order that it appears, so place it wherever it seems most logical to you. Once the tag is in place, your pages will begin being cached.

**Warning:** Because of the way Obullo stores content for output, caching will only work if you are generating display for your controller with a [view](/docs/general/#views).

**Note:** Before the cache files can be written you must set the file permissions on your <dfn>app/system/cache</dfn> folder such that it is writable.

Deleting Caches

------

If you no longer wish to cache a file you can remove the caching tag and it will no longer be refreshed when it expires.

**Note:** Removing the tag will not delete the cache immediately. It will have to expire normally. If you need to remove it earlier you will need to manually delete it from your cache folder.

### Gzip Compression

------

Gzip enables output compression for faster page loads. When enabled, the output class will test whether your server supports Gzip. Even if it does, however, not all browsers support compression so enable only if you are reasonably sure your visitors can handle it.

To enable gzip compression go <dfn>app/config/cache.php</dfn> and change compress_ouput value. 

```php
$cache['compress_output']       = TRUE;  // Compress switch
$cache['compression_level']     = 8;     // Compress level
```

### How Can I Test the Page is Compressed ?

------

There are three way to make sure you are actually serving up compressed content.
<ol>
    <li>View the headers: Use [Live HTTP Headers](https://addons.mozilla.org/en-US/firefox/addon/live-http-headers/) to examine the response. Look for a line that says "Content-encoding: gzip".</li>
   <li>Firefox browser: Use Web Developer Toolbar > Information > View Document Size to see whether the page is compressed.</li>
    <li>Online: Use the [online gzip test](http://www.gidnetwork.com/tools/gzip-test.php) to check whether your page is compressed.</li></ol>

**Note:** Above the topics related about online test not for the localhost.

If you are getting a blank page when compression is enabled it means you are prematurely outputting something to your browser. It could even be a line of whitespace at the end of one of your scripts. For compression to work, nothing can be sent before the output buffer is called by the output class.

**Critical:** Do not *"echo"* any values with compression enabled this may cause server crashes.

## Profiling Your Application<a name="profiling-your-application"></a>

### Profiling Your Application

------

The Profiler Class will display benchmark results, queries you have run, and $_POST data at the bottom of your pages. This information can be useful during development in order to help with debugging and optimization.

### Initializing the Profiler Class

------

**Important:**  This class does <kbd>NOT</kbd> need to be initialized. It is loaded automatically by the [Output Class](/docs/packages/#output-class) if profiling is enabled as shown below.

### Enabling the Profiler

------

To enable the profiler place the following function anywhere within your [Controller](/docs/general/#controllers) functions:

```php
$this->output->profiler();
```

When enabled a report will be generated in a <b>popup window</b>.

To disable the profiler you will use:

```php
$this->output->profiler(FALSE);
```

Profiler popup window content looks like this

```php
URI String
No URI data exists

Directory / Class / Method
welcome / start / index

Memory Usage
1,009,576 bytes

Benchmarks
Loading Time Base Classes      0.0095

Execution Time ( Welcome / Start / Index )      0.0103
Total Execution Time      0.0199

GET Data
No GET data exists

POST Data
No POST data exists

Loaded Files
Config Files      
Lang Files              profiler
Base Helpers            view
                        head_tag
head_tag
Application Helpers      -
Loaded Helpers          input
                        lang
                        benchmark
lang
benchmark
Local Helpers           -
Libraries               -
Models                  -
Databases               -
Scripts                 welcome
Local Views             MODULES\welcome\views\view_welcome.php
Application Views       APP\views\view_base_layout.php
```

### Setting Benchmark Points

------

In order for the Profiler to compile and display your benchmark data you must name your mark points using specific syntax.

Please read the information on setting Benchmark points in [Benchmark Helper](/docs/packages/#benchmarking-helper) page.

## Security<a name="security"></a>

### Security

------

This page describes some "best practices" regarding web security, and details Obullo's internal security features.

### URI Security

------

Obullo is fairly restrictive regarding which characters it allows in your URI strings in order to help minimize the possibility that malicious data can be passed to your application. URIs may only contain the following:

<ul>
    <li>Alpha-numeric text</li>
    <li>Tilde: ~</li>
    <li>Period: .</li>
    <li>Colon: :</li>
    <li>Underscore: _</li>
    <li>Dash: -</li></ul>

### GET, POST, and COOKIE Data

------

GET data is simply disallowed by Obullo since the system utilizes URI segments rather than traditional URL query strings (unless you have the query string option enabled in your config file). The global GET array is <b>unset</b> by the Input class during system initialization.

### Register_globals

------

During system initialization all global variables are unset, except those found in the $_POST and $_COOKIE arrays. The unsetting routine is effectively the same as register_globals = off.

### magic_quotes_runtime

------

The magic_quotes_runtime directive is turned off during system initialization so that you don't have to remove slashes when retrieving data from your database.

### Best Practices

------

Before accepting any data into your application, whether it be POST data from a form submission, COOKIE data, URI data, XML-RPC data, or even data from the SERVER array, you are encouraged to practice this three step approach:

<ol>
    <li>Filter the data as if it were tainted.</li>
    <li>Validate the data to ensure it conforms to the correct type, length, size, etc. (sometimes this step can replace step one)</li>
    <li>Escape the data before submitting it into your database.</li></ol>

Obullo provides the following functions to assist in this process:

<ul>
    <li><h3>XSS Filtering</h3></li>
<hr>

    Obullo comes with a Cross Site Scripting filter. This filter looks for commonly used techniques to embed malicious Javascript into your data, or other types of code that attempt to hijack cookies or do other malicious things. The XSS Filter is described [here](/docs/helpers/#security-helpers).
    <li><h3>Validate the data</h3></li>
    <hr>

    Obullo has a <a href="/ob/validator/releases/0.0.1/#validator-class">Validator Class</a> that assists you in validating, filtering, and prepping your data.
    <li><h3>Escape all data before database insertion</h3></li>
        <hr>
    Never insert information into your database without escaping it. Please see the section that discusses <a href="/docs/database/#running-and-escaping-queries">running queries</a> for more information.</ul>
