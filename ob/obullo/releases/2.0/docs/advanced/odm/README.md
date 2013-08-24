## ODM (Object Database Model)<a name="object-database-model"></a>

### What is the ODM

Obullo has a Odm class who want to do validation in model instead of controller. Odm class use [Validator Class](/ob/validator/releases/0.0.1) to form validations. Using Validation Model you can create Native or <b>Ajax</b> forms easily.

If you want to use Odm Class simply extend your model to it.

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
     $user = new Model\User();
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
$user->where('usr_id', 5);   // you can use post/get variable .. e.g. i\post('usr_id')
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

Some times we don't want to save some fields or that the fields which we haven't got in the db tables we have to validate them.Overcome to this problem we use $model->noSave(); function.

```php
$member = new Model\Member(false);

$model->usr_first_lastname   = i\getPost('usr_first_lastname');
$model->usr_password         = i\getPost('usr_password');

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
<tr><td>xss_clean</td><td>No</td><td>Runs the data through the XSS filtering function, described in the [Security Helper](/ob/security/releases/0.0.1/) page.</td></tr>
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

If you use ajax requests you need to [Form Json Helper](/ob/form_json/releases/0.0.1/) to encode Validaton Model Response in Json format. If you use Obullo Jquery Form Plugin the following is a list of all the Form plugin functions that are available to use

you can control the form plugin functions using the class <b>attribute</b>

```php
<? echo form\open('/welcome/start/doPost.json', array('method' => 'POST', 'class' => 'hide-form no-top-msg'));?>
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

After that load the <b>Obullo JQuery Form Plugin</b> just create a doPost() function in your controller then you need add a <samp>form\open('module/controller/doPost.json');</samp> code in your view file, remember we have a ajax form tutorial in modules/test folder. You can look at there for more details.

```php
function doPost() 
{
    $user = new Model\User(false);  // Include user model
    new form_Json\start();

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
    new form_Json\start();

    
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
    echo form_Json\error($this->db->lastQuery());
    return;
}
```

### Transaction Support

------

Vmodel Library automatically support the transactions if your table engine setted correctly as INNODB. If you want to learn more details about transactions look at [database transactions](/ob/obullo/releases/2.0/docs/database/database-transactions/README.md) section.