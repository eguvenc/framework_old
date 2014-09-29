## Validator Class

This class provides a comprehensive form validation and data prepping class that helps minimize the amount of code you'll write.

**Note:** This class is a <kbd>component</kbd> defined in your package.json. You can <kbd>replace components</kbd> with third-party packages.

**Note:** This document refers to using validations in controllers so it is <strong>optionally</strong> available for those who want to use a more traditional validation approach.

**Note:** In other words we use <kbd>Odm</kbd> Package to save and validate the data, which is easier. Look at the <kbd>odm package docs</kbd> for more details.


<ul>
<li><a href="#overview">Overview</a></li>
<li><a href="#form-validation-tutorial">Form Validation Tutorial</a>
    <ul>
        <li><a href="#the-form">The Form Validations</a></li>
        <li><a href="#the-success-page">The Success Page</a></li>
        <li><a href="#the-controller">The Controller</a></li>
        <li><a href="#setting-validation-rules">Setting Validation Rules</a></li>
        <li><a href="#setting-validation-rules-using-an-array">Setting Validation Rules Using an Array</a></li>
        <li><a href="#cascading-rules">Cascading Rules</a></li>
        <li><a href="#prepping-data">Prepping Data</a></li>
        <li><a href="#re-populating-the-form">Re-populating the Form</a></li>
        <li><a href="#callbacks">Callbacks</a></li>
        <li><a href="#setting-error-messages">Setting Error Messages</a></li>
        <li><a href="#changing-the-error-delimiters">Changing the Error Delimiters</a></li>
        <li><a href="#translating-field-names">Translating Field Names</a></li>
        <li><a href="#showing-errors-individually">Showing Errors Individually</a></li>
        <li><a href="#saving-sets-of-validation">Saving Sets of Validation Rules to a Config File</a></li>
        <li><a href="#using-arrays-as-field-name">Using Arrays as Field Name</a></li>
    </ul>    
</li>
<li><a href="#rule-reference">Rule Reference</a></li>    
<li><a href="#prepping-reference">Prepping Reference</a></li>    
<li><a href="#function-reference">Function Reference</a></li>    
<li><a href="#helper-reference">Helper Reference</a></li>    
</ul>

### Overview <a name="overview"></a>

------

Before explaining traditional approach to data validation, let's describe the ideal scenario:

1. A form is displayed.
2. You fill and submit it.
3. If you submit something invalid, or perhaps miss a required item, the form is redisplayed containing your data along with an error message describing the problem.
4. This process continues until you submit a valid form.

On the receiving end, the script must:

1. Check for the required data.
2. Verify that the data is of the correct type, and meets the correct criteria. For example, if a username is submitted it must be validated to contain only permitted characters. It must be of a minimum length, and not exceed a maximum length. The username can't be someone else's existing username, or perhaps even a reserved word. Etc.
3. Sanitize the data for security.
4. Pre-format the data if needed (Does the data need to be trimmed? HTML encoded? Etc.)
5. Prep the data for insertion into a database.

Although there is nothing terribly complex about the above process, it usually requires a significant amount of code, and to display error messages, various control structures are usually placed within the form HTML. Form validation, while simple to create, is generally very messy and tedious to implement.

### Form Validation Tutorial <a name="form-validation-tutorial"></a>

------

What follows is a "hands on" tutorial for implementing Obullos Form Validation.

In order to implement form validation you'll need three things:

1. A view file containing a form. ( look at <kbd>/docs/general/views</kbd> )
2. A view file containing a "success" message to be displayed upon successful submission.
3. A controller function to receive and process the submitted data. ( <kbd>/docs/general/controllers</kbd> ) 

Let's create those three things, using a member sign-up form as the example.

### The Form Validations <a name="the-form"></a>

------

Create a directory in directories folder called **form_example.**

Using a text editor, create a form called myform.php. In it, place this code and save it to your <samp>.public/form_example/view/</samp> folder:

```php
<?php echo $this->form->getErrorString(); ?>

<?php echo $this->form->open('form_example/form'); ?>

<h5>Username</h5>

<?php echo $this->form->input('username', '', "size='50'"); ?>

<h5>Password</h5>
<?php echo $this->form->password('password', '', "size='50'"); ?>

<h5>Password Confirm</h5>
<?php echo $this->form->password('passconf', '', "size='50'"); ?>

<h5>Email Address</h5>
<?php echo $this->form->input('email', '', "size='50'"); ?>

<div><?php echo $this->form->submit('send_data', 'Send Form'); ?></div>

<?php echo $this->form->close(); ?>
```

### The Success Page <a name="the-success-page"></a>

------

Using a text editor, create a form called <kbd>success.php</kbd>. In it, place this code and save it to your <samp>app/modules/form_example/views/</samp> folder:

```php
<h3>Your form was successfully submitted!</h3>

<p><?php echo $this->url->anchor('forms/form', 'Try it again!'); ?></p>
```

### The Controller <a name="the-controller"></a>

------

Using a text editor, create a controller called <kbd>form.php</kbd>. In it, place this code and save it to your <samp>.public/form_example/controller</samp> folder:

```php
<?php
Class Form_Example extends Controller {
    
    public function index()
    {
        if ($this->form->isValid() == false)
        {
            view('myform');
        }
        else
        {
            view('success');
        }
    }
    
} // end.
?>
```

Try it!

To try your form, visit your site using a URL similar to this one:

```php
example.com/index.php/forms/form_example
```

If you submit the form you should simply see the form reload. That's because you haven't set up any validation rules yet.

**Since you haven't told the Form Validation class to validate anything yet, it returns <samp>false</samp> (boolean false) by default. The <samp>run()</samp> function only returns <samp>true</samp> if it successfully apply your rules without any of them failing.**

### Explanation <a name="explanation"></a>

------

You'll notice several things about the above pages:

The <kbd>form</kbd> ( myform.php ) is a standard web form with a couple exceptions:

It uses a <kbd>form helper</kbd> to create the form opening. Technically, this isn't necessary. You could create the form using standard HTML. However, the benefit of using the helper is that it generates the action URL for you, based on the URL in your config file. This makes your application more portable when your URLs change.
At the top of the form you'll notice the following function call:

```php
<?php echo $this->form->getErrorString() ?>
```

This function will return any error messages sent back by the validator. If there are no messages it returns an empty string.

The <kbd>controller</kbd> (form_example.php) has one function: <kbd>index()</kbd>. This function initializes the validation class and loads the <kbd>form helper</kbd> and <kbd>URL helper</kbd> used by your view files. It also <samp>runs</samp> the validation routine. Based on the success of the validation, it either presents the form or the success page.

### Setting Validation Rules <a name="setting-validation-rules"></a>

------

Obullo lets you set as many validation rules as you need for a given field, cascading them in order, and it even lets you prep and pre-process the field data at the same time. To set validation rules you will use the <kbd>setRules()</kbd> function:

```php
$this->form->setRules();
```

The above function takes **three** parameters as input:

1. The field name - the exact name you've given the form field.
2. A "human" name for this field, which will be inserted into the error message. For example, if your field is named "user" you might give it a human name of "Username". **Note:** If you would like the field name to be stored in a language file, please see Translating Field Names.
3. The validation rules for this form field.

Here is an example. In your <kbd>controller</kbd> (form.php), add this code just below the validation initialization function:

```php
$this->form->setRules('username', 'Username', 'required');
$this->form->setRules('password', 'Password', 'required');
$this->form->setRules('passconf', 'Password Confirmation', 'required');
$this->form->setRules('email', 'Email', 'required');
```

Your controller should now look like this:

```php
<?php
Class Form_Example extends Controller {
    
    public function index()
    {
        $this->form->setRules('username', 'Username', 'required');
        $this->form->setRules('password', 'Password', 'required');
        $this->form->setRules('passconf', 'Password Confirmation', 'required');
        $this->form->setRules('email', 'Email', 'required');
                
        if ($this->form->isValid() == false)
        {
             view('myform');
        }
        else
        {
             view('success');
        }
    }
    
} // end.
?>
```

Now submit the form with the fields blank and you should see the error messages. If you submit the form with all the fields populated you'll see your success page.

**Note:** The form fields are not yet re-populated with the data when there is an error. We'll get to that shortly.

### Setting Rules Using an Array <a name="setting-validation-rules-using-an-array"></a>

------

Before moving on it should be noted that the rule setting function can be passed an array if you prefer to set all your rules in one action. If you use this approach you must name your array keys as indicated:

```php
$config = array(
               array(
                     'field'   => 'username',
                     'label'   => 'Username',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'password',
                     'label'   => 'Password',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'passconf',
                     'label'   => 'Password Confirmation',
                     'rules'   => 'required'
                  ),   
               array(
                     'field'   => 'email',
                     'label'   => 'Email',
                     'rules'   => 'required'
                  )
            );

$this->form->setRules($config);
```

### Cascading Rules <a name="cascading-rules"></a>

------

Obullo lets you pipe multiple rules together. Let's try it. Change your rules in the third parameter of rule setting function, like this:

```php
$this->form->setRules('username', 'Username', 'required|minLen[5]|maxLen[12]');
$this->form->setRules('password', 'Password', 'required|matches[passconf]');
$this->form->setRules('passconf', 'Password Confirmation', 'required');
$this->form->setRules('email', 'Email', 'required|validEmail');
```

The above code sets the following rules:

1. The username field must not be shorter than 5 characters and no longer than 12.
2. The password field must match the password confirmation field.
3. The email field must contain a valid email address.

Give it a try! Submit your form without the proper data and you'll see new error messages that correspond to your new rules. There are numerous rules available which you can read about in the validation reference.

### Prepping Data <a name="prepping-data"></a>

------

In addition to the validation functions like the ones we used above, you can also prep your data in various ways. For example, you can set up rules like this:

```php
$this->form->setRules('username', 'Username', 'trim|required|minLen[5]|maxLen[12]|xssClean');
$this->form->setRules('password', 'Password', 'trim|required|matches[passconf]|md5');
$this->form->setRules('passconf', 'Password Confirmation', 'trim|required');
$this->form->setRules('email', 'Email', 'trim|required|validEmail');
```

In the above example, we are "trimming" the fields, converting the password to MD5, and running the username through the "xssClean" function, which removes malicious data.

**Any native PHP function that accepts one parameter can be used as a rule, like** <kbd>htmlspecialchars, trim, MD5,</kbd> **etc.**

**Note:** You will generally want to use the prepping functions **after** the validation rules so if there is an error, the original data will be shown in the form.

### Re-populating the form <a name="re-populating-the-form"></a>

------

Thus far we have only been dealing with errors. It's time to repopulate the form field with the submitted data. Validator offers several helper functions that permit you to do this. The one you will use most commonly is:

```php
$this->form->setValue('fieldname')
```

Open your <kbd>myform.php</kbd> view file and update the **value** in each field using the <kbd>setValue()</kbd> function:

**Don't forget to include each. field name in the** <kbd>setValue()</kbd> **functions!**

```php
<?php echo $this->form->getErrorString(); ?>

<?php echo $this->form->open('form_example/form'); ?>

<h5>Username</h5>
<?php echo $this->form->input('username', setValue('username'), "size='50'"); ?>

<h5>Password</h5>
<?php echo $this->form->password('password', setValue('password'), "size='50'"); ?>

<h5>Password Confirm</h5>
<?php echo $this->form->password('passconf', setValue('passconf'), "size='50'"); ?>

<h5>Email Address</h5>
<?php echo $this->form->input('email', setValue('email'), "size='50'"); ?>

<div><?php echo $this->form->submit('send_data', 'Send Form'); ?></div>

<?php echo $this->form->close(); ?>
```

<kbd>Now reload your page and submit the form so that it triggers an error. Your form fields should now be re-populated</kbd>

**Note:** The Function Reference section below contains functions that permit you to re-populate (select) menus, radio buttons, and checkboxes.
**Important Note:** If you use an array as the name of a form field, you must supply it as an array to the function. Example:

```php
<?php echo $this->form->input('colors[]', $this->form->setValue('colors[]'), "size='50'"); ?>
```

For more info please see the [Using Arrays as Field Names](#using-arrays-as-field-name) section below.

### Callbacks: Your own Validation Functions <a name="callbacks"></a>

------

The validation system supports callbacks to your own validation functions. This permits you to extend the validation class to meet your needs. For example, if you need to run a database query to see if the user is choosing a unique username, you can create a callback function that does that. Let's create a example of this.

In your controller, change the "username" rule to this:

```php
$this->form->setRules('username', 'Username', 'callback_usernameCheck');
```

Then add a new function called <kbd>usernameCheck</kbd> to your controller. Here's how your controller should now look:

```php
<?php

Class Form_Example extends Controller {
    
    public function index()
    {
        $this->form->setRules('username', 'Username', 'required|callback_usernameCheck');
        $this->form->setRules('password', 'Password', 'required');
        $this->form->setRules('passconf', 'Password Confirmation', 'required');
        $this->form->setRules('email', 'Email', 'required');
                
        if ($this->form->isValid() == false)
        {
            view('myform');
        }
        else
        {
            view('success');
        }
    }

    public function usernameCheck($str)
    {     
        if ($str == 'test')
        {
            $this->form->setMessage('usernameCheck', 'The %s field can not be the word "test"');
            return false;
        }
        else
        {
            return true;
        }
    }
    
} // end.
?>
```

<kbd>Reload your form and submit it with the word "test" as the username. You can see that the form field data was passed to your callback function for you to process.</kbd>

**To invoke a callback just put the function name in a rule, with "callback_" as the rule prefix.**

You can also process the form data that is passed to your callback and return it. If your callback returns anything other than a boolean true/false it is assumed that the data is your newly processed form data.

### Setting Error Messages <a name="setting-error-messages"></a>

------

All of the native error messages are located in the following language file: <kbd>app/translations/en-US/validator.php</kbd>

To set your own custom message you can either edit that file, or use the following function:

```php
$this->form->setMessage('rule', 'Error Message');
```

Where <var>rule</var> corresponds to the name of a particular rule, and <var>Error Message</var> is the text you would like to be displayed.

If you include <kbd>%s</kbd> in your error string, it will be replaced with the "human" name you used for your field when you set your rules.

In the **"callback"** example above, the error message was set by passing the name of the function:

```php
$this->form->setMessage('usernameCheck')
```

You can also override any error message found in the language file. For example, to change the message for the "required" rule you will do this:

```php
$this->form->setMessage('required', 'Your custom message here');
```

### Translating Field Names <a name="translating-field-names"></a>

------

If you would like to store the "human" name you passed to the <kbd>setRules()</kbd> function in a language file, and therefore make the name able to be translated, here's how:

First, prefix your "human" name with <kbd>translate:</kbd>, as in this example:

```php
$this->form->setRules('first_name', 'translate:first_name', 'required');
```

Then, store the name in one of your language file arrays (without the prefix):

```php
$translate['first_name'] = 'First Name';
```

**Note:** If you store your array item in a language file that is not loaded automatically by Framework, you'll need to remember to load it in your controller using:

```php
$this->translator->load('filename');
```

See the [Language Helper](#) page for more info regarding language files.

### Changing the Error Delimiters <a name="changing-the-error-delimiters"></a>

------

By default, the Validator Class adds a paragraph tag (

) around each error message shown. You can change these delimiters either globally or individually.

1. **Changing delimiters Globally**

    To globally change the error delimiters, in your controller function, just after loading the Form Validation class, add this:
    ```php
    $this->form->setErrorDelimiters('<div class="error">', '</div>');
    ```

    In this example, we've switched to using div tags.

2. **Changing delimiters Individually**

   Each of the two error generating functions shown in this tutorial can be supplied their own delimiters as follows:

```php
<?php echo $this->form->error('field name', '<div class="error">', '</div>'); ?>
```

Or:

```
<?php echo $this->form->getErrorString('<div class="error">', '</div>'); ?>
```

### Showing Errors Individually <a name="showing-errors-individually"></a>

------

If you prefer to show an error message next to each form field, rather than as a list, you can use the <kbd>$this->form->error()</kbd> function.

Try it! Change your form so that it looks like this:

```php
<h5>Username</h5>
<?php echo $this->form->error('username'); ?>
<input type="text" name="username" value="<?php echo $this->form->setValue('username'); ?>" size="50" />

<h5>Password</h5>
<?php echo $this->form->error('password'); ?>
<input type="text" name="password" value="<?php echo $this->form->setValue('password'); ?>" size="50" />

<h5>Password Confirm</h5>
<?php echo $this->form->error('passconf'); ?>
<input type="text" name="passconf" value="<?php echo $this->form->setValue('passconf'); ?>" size="50" />

<h5>Email Address</h5>
<?php echo $this->form->error('email'); ?>
<input type="text" name="email" value="<?php echo $this->form->setValue('email'); ?>" size="50" />
```

If there are no errors, nothing will be shown. If there is an error, the message will appear.

**Important Note:** If you use an array as the name of a form field, you must supply it as an array to the function. Example:

```php
<?php echo $this->form->error('options[size]'); ?>
<input type="text" name="options[size]" value="<?php echo set_value("options[size]"); ?>" size="50" /> 
```

For more info please see the [Using Arrays as Field Names](#using-arrays-as-field-name) section below.

### Saving Sets of Validation Rules to a Config File <a name="saving-sets-of-validation"></a>

------

A nice feature of the Form Validation class is that it permits you to store all your validation rules for your entire application in a config file. You can organize these rules into "groups". These groups can either be loaded automatically when a matching controller/function is called, or you can manually call each set as needed.

#### How to save your rules 

To store your validation rules, simply create a file named <samp>form_validation.php</samp> in your <kbd>app/config/</kbd> folder. In that file you will place an array named <samp>$config</samp> with your rules. As shown earlier, the validation array will have this prototype:

```php
$config = array(
               array(
                     'field'   => 'username',
                     'label'   => 'Username',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'password',
                     'label'   => 'Password',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'passconf',
                     'label'   => 'Password Confirmation',
                     'rules'   => 'required'
                  ),   
               array(
                     'field'   => 'email',
                     'label'   => 'Email',
                     'rules'   => 'required'
                  )
            );
```

<kbd>Your validation rule file will be loaded automatically and used when you call the run() function.</kbd>

**Please note that you MUST name your array $config.**


#### Creating Sets of Rules

In order to organize your rules into "sets", it requires that you place them into "sub arrays". Consider the following example, showing two sets of rules. We've arbitrarily called these two rules "signup" and "email". You can name your rules anything you want:

```php
$config = array(
                 'signup' => array(
                                    array(
                                            'field' => 'username',
                                            'label' => 'Username',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'password',
                                            'label' => 'Password',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'passconf',
                                            'label' => 'PasswordConfirmation',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'required'
                                         )
                                    ),
                 'email' => array(
                                    array(
                                            'field' => 'emailaddress',
                                            'label' => 'EmailAddress',
                                            'rules' => 'required|validEmail'
                                         ),
                                    array(
                                            'field' => 'name',
                                            'label' => 'Name',
                                            'rules' => 'required|alpha'
                                         ),
                                    array(
                                            'field' => 'title',
                                            'label' => 'Title',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'message',
                                            'label' => 'MessageBody',
                                            'rules' => 'required'
                                         )
                                    )                          
               );
```

#### Calling a Specific Rule Group

In order to call a specific group you will pass its name to the run() function. For example, to call the <samp>signup</samp> rule you will do this:

```php
if ($this->form->run('signup') == false)
{
    view('myform');
}
else
{
    view('success');
}
```

#### Associating a Controller Function with a Rule Group

An alternative (and more automatic) method of calling a rule group is to name it according to the controller class/function you intend to use it with. For example, let's say you have a controller named <samp>Member</samp> and a function named <samp>signup</samp>. Here's what your class might look like:

```php
Class Member extends Controller {

   public function signup()
   {      
      $this->form = $this->form->validatorGetInstance();
            
      if ($this->form->run() == false)
      {
          view('myform');
      }
      else
      {
          view('success');
      }
   }

} // end.
```


In your validation config file, you will name your rule group <samp>member/signup</samp>:

```php
$config = array(
           'member/signup' => array(
                                    array(
                                            'field' => 'username',
                                            'label' => 'Username',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'password',
                                            'label' => 'Password',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'passconf',
                                            'label' => 'PasswordConfirmation',
                                            'rules' => 'required'
                                         ),
                                    array(
                                            'field' => 'email',
                                            'label' => 'Email',
                                            'rules' => 'required'
                                         )
                                    )
               );
```

<kbd>When a rule group is named identically to a controller class/function it will be used automatically when the run() function is invoked from that class/function.</kbd>

### Using Arrays as Field Names <a name="using-arrays-as-field-name"></a>

------

The Form Validator class supports the use of arrays as field names. Consider this example:

```php
<input type="text" name="options[]" value="" size="50" />
```

If you do use an array as a field name, you must use the EXACT array name in the [Helper Functions](#helper-reference) that require the field name, and as your Validation Rule field name.

For example, to set a rule for the above field you would use:

```php
$this->form->setRules('options[]', 'Options', 'required');
```

Or, to show an error for the above field you would use:

```php
<?php echo $this->form->error('options[]'); ?>
```

Or to re-populate the field you would use:

```php
<input type="text" name="options[]" value="<?php echo set_value('<kbd>options[]</kbd>'); ?>" size="50" />
```

You can use multidimensional arrays as field names as well. For example:

```php
<input type="text" name="options[size]" value="" size="50" />
```

Or even:

```php
<input type="text" name="sports[nba][basketball]" value="" size="50" />
```

As with our first example, you must use the exact array name in the helper functions:

```php
<?php echo $this->form->error('sports[nba][basketball]'); ?>
```

If you are using checkboxes (or other fields) that have multiple options, don't forget to leave an empty bracket after each option, so that all selections will be added to the POST array:

```php
<input type="checkbox" name="options[]" value="red" />
<input type="checkbox" name="options[]" value="blue" />
<input type="checkbox" name="options[]" value="green" /> 
```

Or if you use a multidimensional array:

```php
<input type="checkbox" name="options[color][]" value="red" />
<input type="checkbox" name="options[color][]" value="blue" />
<input type="checkbox" name="options[color][]" value="green" /> 
```

When you use a helper function you'll include the bracket as well:

```php
<?php echo $this->form->error('options[color][]'); ?>
```

### Rule Reference <a name="rule-reference"></a>

------

The following is a list of all the native rules that are available to use:

<table>
<thead>
<tr>
<th>Rule</th>
<th>Parameter</th>
<th>Description</th>
<th>Example</th>
</tr>
</thead>
<tbody>
<tr>
<td>_int</td>
<td>No</td>
<td>Sets data type to integer.</td>
<td></td>
</tr>
<tr>
<td>_string</td>
<td>No</td>
<td>Sets data type to string.</td>
<td></td>
</tr>
<tr>
<td>_array</td>
<td>No</td>
<td>Sets data type to array.</td>
<td></td>
</tr>
<tr>
<td>_bool</td>
<td>No</td>
<td>Sets data type to boolean.</td>
<td></td>
</tr>
<tr>
<td>_boolean</td>
<td>No</td>
<td>Alias of bool.</td>
<td></td>
</tr>
<tr>
<td>required</td>
<td>No</td>
<td>Returns false if the form element is empty.</td>
<td></td>
</tr>
<tr>
<td>contains</td>
<td>Yes</td>
<td>Returns false if the form element has unaccepted values.</td>
<td>contains(1) or contains(foo,bar), contains(1,3,9)</td>
</tr>
<tr>
<td>matches</td>
<td>Yes</td>
<td>Returns false if the form element does not match the one in the parameter.</td>
<td>matches(form_item)</td>
</tr>
<tr>
<td>minLen</td>
<td>Yes</td>
<td>Returns false if the form element is shorter then the parameter value.</td>
<td>minLen(6)</td>
</tr>
<tr>
<td>maxLen</td>
<td>Yes</td>
<td>Returns false if the form element is longer then the parameter value.</td>
<td>maxLen(12)</td>
</tr>
<tr>
<td>exactLen</td>
<td>Yes</td>
<td>Returns false if the form element is not exactly the parameter value.</td>
<td>exactLen(8)</td>
</tr>
<tr>
<td>alpha</td>
<td>No</td>
<td>Returns false if the form element contains anything other than alphabetical characters.</td>
<td></td>
</tr>
<tr>
<td>alphaNumeric</td>
<td>No</td>
<td>Returns false if the form element contains anything other than alpha-numeric characters.</td>
<td></td>
</tr>
<tr>
<td>alphaDash</td>
<td>No</td>
<td>Returns false if the form element contains anything other than alpha-numeric characters, underscores or dashes.</td>
<td></td>
</tr>
<tr>
<td>isDecimal</td>
<td>No</td>
<td>Returns false if the form element contains anything other than decimal characters.</td>
<td></td>
</tr>
<tr>
<td>isNumeric</td>
<td>No</td>
<td>Returns false if the form element contains anything other than numeric characters.</td>
<td></td>
</tr>
<tr>
<td>isInteger</td>
<td>No</td>
<td>Returns false if the form element contains anything other than an integer.</td>
<td></td>
</tr>
<tr>
<td>isNatural</td>
<td>No</td>
<td>Returns false if the form element contains anything other than a natural number: 0, 1, 2, 3, etc.</td>
<td></td>
</tr>
<tr>
<td>isNaturalNoZero</td>
<td>No</td>
<td>Returns false if the form element contains anything other than a natural number, but not zero: 1, 2, 3, etc.</td>
<td></td>
</tr>
<tr>
<td>validEmail</td>
<td>No</td>
<td>Returns false if the form element does not contain a valid email address.</td>
<td></td>
</tr>
<tr>
<td>validEmailDns</td>
<td>No</td>
<td>Returns false if the form element does not contain a valid email AND dns query return to false.</td>
<td></td>
</tr>
<tr>
<td>validEmails</td>
<td>Yes</td>
<td>Returns false if any value provided in a comma separated list is not a valid email. (If parameter true or 1 function also will do a dns query foreach emails)</td>
<td>validEmails(true)</td>
</tr>
<tr>
<td>validIp</td>
<td>No</td>
<td>Returns false if the supplied IP is not valid.</td>
<td></td>
</tr>
<tr>
<td>validBase64</td>
<td>No</td>
<td>Returns false if the supplied string contains anything other than valid Base64 characters.</td>
<td></td>
</tr>
<tr>
<td>noSpace</td>
<td>No</td>
<td>Returns false if the supplied string contains space characters.</td>
<td></td>
</tr>
<tr>
<td>callback_function(param)</td>
<td>Yes</td>
<td>You can define a custom callback function which is a class method located in your current model or just a function.</td>
<td>callback_functionname(param)</td>
</tr>
<tr>
<td>validDate</td>
<td>Yes</td>
<td>Returns false if the supplied date is not valid in current format. Enter your date format, default is mm-dd-yyyy.</td>
<td>validDate(yyyy-mm-dd)</td>
</tr>

</tbody>
</table>
    
**Note:** These rules can also be called as discrete functions. For example:

```php
$this->form->required($string);
```

**Note:** You can also use any native PHP functions that permit one parameter.

### Prepping Reference <a name="prepping-reference"></a>

------

The following is a list of all the prepping functions that are available to use:

<table>
    <thead>
            <tr>
                <th>Name</th>
                <th>Parameter</th>
                <th>Description</th>
            </tr>
    </thead>
    <tbody>
            <tr>
                <td>xssClean</td>
                <td>No</td>
                <td>Runs the data through the XSS filtering function, described in the <kbd>Security Helper</kbd> package.</td>
            </tr>
            <tr>
                <td>prepForForm</td>
                <td>No</td>
                <td>Converts special characters so that HTML data can be shown in a form field without breaking it.</td>
            </tr>
            <tr>
                <td>prepUrl</td>
                <td>No</td>
                <td>Adds "http://" to URLs if missing.</td>
            </tr>
            <tr>
                <td>stripImageTags</td>
                <td>No</td>
                <td>Strips the HTML from image tags leaving the raw URL.</td>
            </tr>
            <tr>
                <td>encodePhpTags</td>
                <td>No</td>
                <td>Converts PHP tags to entities.</td>
            </tr>
    </tbody>
</table>
  
**Note:** You can also use any native PHP functions that permit one parameter, like <samp>trim, htmlspecialchars, urldecode,</samp> etc.

### Function Reference <a name="function-reference"></a>

-------

The following functions are intended to be used in your controller functions.

#### $this->form->setRules();

Permits you to set validation rules, as described in the tutorial sections above:

* [Setting Validation Rules](#setting-validation-rules)
* [Saving Groups of Validation Rules to a Config File](#saving-sets-of-validation)

#### $this->form->run();

Runs the validation routines. Returns boolean true on success and false on failure. You can optionally pass the name of the validation group via the function, as described in: [Saving Groups of Validation Rules to a Config File](#saving-sets-of-validation).

#### $this->form->setMessage();

Permits you to set custom error messages. See [Setting Error Messages](#setting-error-messages) above.

### Helper Reference <a name="helper-reference"></a>

------

The following helper functions are available for use in the view files containing your forms. Note that these are procedural functions, so they **do not** require you to prepend them with $this->form.

#### $this->form->error()

Shows an individual error message associated with the field name supplied to the function. Example:

```php
<?php echo $this->form->error('username'); ?>
```

The error delimiters can be optionally specified. See the [Changing the Error Delimiters](#changing-the-error-delimiters) section above.

#### $this->form->isError($field, $return_string = 'error')

Shows an individual error message associated with the field name supplied to the function and it returns to string if supplied an error string otherwise it returns to **booelan**

```php
<?php var_dump( $this->form->isError('username') );  // boolean 
echo $this->form->isError('username', 'error');  //  "error" string
?>
```

#### $this->form->getErrorString()

Shows all error messages as a string: Example:

```php
<?php echo $this->form->getErrorString(); ?>
```

The error delimiters can be optionally specified. See the [Changing the Error Delimiters](#changing-the-error-delimiters) section above.

#### $this->form->setValue()

Permits you to set the value of an input form or textarea. You must supply the field name via the first parameter of the function. The second (optional) parameter allows you to set a default value for the form. Example:

```php
<input type="text" name="quantity" value="<?php echo $this->form->setValue('quantity', '0'); ?>" size="50" />
```

The above form will show "0" when loaded for the first time.

#### $this->form->setSelect()

If you use a <kbd>(select)</kbd> menu, this function permits you to display the menu item that was selected. The first parameter must contain the name of the select menu, the second parameter must contain the value of each item, and the third (optional) parameter lets you set an item as the default (use boolean true/false).

Example:

```php
<select name="myselect">
<option value="one" <?php echo $this->form->setSelect('myselect', 'one', true); ?> >One</option>
<option value="two" <?php echo $this->form->setSelect('myselect', 'two'); ?> >Two</option>
<option value="three" <?php echo $this->form->setSelect('myselect', 'three'); ?> >Three</option>
</select>
``` 

#### $this->form->setCheckbox()

Permits you to display a checkbox in the state it was submitted. The first parameter must contain the name of the checkbox, the second parameter must contain its value, and the third (optional) parameter lets you set an item as the default (use boolean true/false). Example:

```php
<input type="checkbox" name="mycheck[]" value="1" <?php echo $this->form->setCheckbox('mycheck[]', '1'); ?> />
<input type="checkbox" name="mycheck[]" value="2" <?php echo $this->form->setCheckbox('mycheck[]', '2'); ?> />
```

#### $this->form->setRadio()

Permits you to display radio buttons in the state they were submitted. This function is identical to the set_checkbox() function above.

```php
<input type="radio" name="myradio" value="1" <?php echo $this->form->setRadio('myradio', '1', true); ?> />
<input type="radio" name="myradio" value="2" <?php echo $this->form->setRadio('myradio', '2'); ?> />
```

#### $this->form->setNotice($message);

Sets a flash notice for the general form error or success.

#### $this->form->getNotice();

Gets the stored notification message from current session.