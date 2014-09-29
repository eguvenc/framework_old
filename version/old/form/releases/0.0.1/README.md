## Form Class

The Form Class file contains functions that assist in working with forms.

### Initializing the Class

-------

```php
new Form;
$this->form->method();
```

The following functions are available:

#### $this->form->open()

Creates an opening form tag with a base URL <b>built from your config preferences</b>. It will optionally let you add form attributes and hidden input fields.

The main benefit of using this tag rather than hard coding your own HTML is that it permits your site to be more portable in the event your URLs ever change.

Here's a simple example:

```php
echo $this->form->open('email/send');
```

The above example would create a form that points to your base URL plus the "email/send" URI segments, like this:

```php
<form method="post" action="http:/example.com/index.php/email/send" />
```

<b>Adding Attributes</b>

Attributes can be added by passing an associative array to the second parameter, like this:

```php
$attributes = array('class' => 'email', 'id' => 'myform');

echo $this->form->open('email/send', $attributes);
```

The above example would create a form similar to this:

```php
<form method="post" action="http:/example.com/index.php/email/send"  class="email"  id="myform" />
```

<b>Adding Hidden Input Fields</b>

Hidden fields can be added by passing an associative array to the third parameter, like this:

```php
$hidden = array('username' => 'Joe', 'member_id' => '234');

echo $this->form->open('email/send', '', $hidden);
```

The above example would create a form similar to this:

```php
<form method="post" action="http:/example.com/index.php/email/send">
<input type="hidden" name="username" value="Joe" />
<input type="hidden" name="member_id" value="234" />
```

#### $this->form->openMultipart()

This function is absolutely identical to the $this->form->open() tag above except that it adds a multipart attribute, which is necessary if you would like to use the form to upload files with.

#### $this->form->hidden('name', 'value' , $attributes = '')

Lets you generate hidden input fields. You can either submit a name/value string to create one field:

```php
$this->form->hidden('username', 'johndoe',  $attr = " id='username' " );

// Would produce:
<input type="hidden" name="username" value="johndoe" id='username'  />
```

Or you can submit an associative array to create multiple fields:

```php
$data = array(
              'name'  => 'John Doe',
              'email' => 'john@example.com',
              'url'   => 'http://example.com'
            );

echo $this->form->hidden($data);

// Would produce:

<input type="hidden" name="name" value="John Doe" />
<input type="hidden" name="email" value="john@example.com" />
<input type="hidden" name="url" value="http://example.com" />
```

#### $this->form->input('name', 'value',$attributes = '')

Lets you generate a standard text input field. You can minimally pass the field name and value in the first and second parameter:

```php
echo $this->form->input('username', 'johndoe', $attributes = '');
```

Or you can pass an associative array containing any data you wish your form to contain:

```php
$data = array(
              'name'        => 'username',
              'id'          => 'username',
              'value'       => 'johndoe',
              'maxlength'   => '100',
              'size'        => '50',
              'style'       => 'width:50%',
            );

echo $this->form->input($data);

// Would produce:

<input type="text" name="username" id="username" value="johndoe" maxlength="100" size="50" style="width:50%" />
```

If you would like your form to contain some additional data, like Javascript, you can pass it as a string in the third parameter:

```php
$js = 'onclick="someFunction()"';

echo $this->form->input('username', 'johndoe', $js);
```

#### $this->form->password()

This function is identical in all respects to the <dfn>$this->form->input()</dfn> function above except that it sets a "password" type.

#### $this->form->upload()

This function is identical in all respects to the <dfn>$this->form->input()</dfn> function above except that it sets a "file" type, allowing it to be used to upload files.

#### $this->form->textarea()

This function is identical in all respects to the <dfn>$this->form->input()</dfn> function above except that it generates a "textarea" type. Note: Instead of the "maxlength" and "size" attributes in the above example, you will specify "rows" and "cols".

#### $this->form->dropdown()

Lets you create a standard drop-down field. The first parameter will contain the name of the field, the second parameter will contain an associative array of options, and the third parameter will contain the value you wish to be selected. You can also pass an array of multiple items through the third parameter, and Obullo will create a multiple select for you. Example:

```php
$options = array(
                  'small'  => 'Small Shirt',
                  'med'    => 'Medium Shirt',
                  'large'   => 'Large Shirt',
                  'xlarge' => 'Extra Large Shirt',
                );

$shirtsOnSale = array('small', 'large');

echo $this->form->dropdown('shirts', $options, 'large');

// Would produce:

<select name="shirts">
<option value="small">Small Shirt</option>
<option value="med">Medium Shirt</option>
<option value="large" selected="selected">Large Shirt</option>
<option value="xlarge">Extra Large Shirt</option>
</select>

echo $this->form->dropdown('shirts', $options, shirtsOnSale);

// Would produce:

<select name="shirts" multiple="multiple">
<option value="small" selected="selected">Small Shirt</option>
<option value="med">Medium Shirt</option>
<option value="large" selected="selected">Large Shirt</option>
<option value="xlarge">Extra Large Shirt</option>
</select>
```

If you would like the opening <b>select</b> to contain additional data, like an id attribute or JavaScript, you can pass it as a string in the fourth parameter:

```php
$js = 'id="shirts" onChange="someFunction();"';

echo $this->form->dropdown('shirts', $options, 'large', $js);
```

If the array passed as $options is a multidimensional array, $this->form->dropdown() will return the array keys as the label.

#### Using Your Schema for Dropwdowns

```php
<?php 

$users = array(
  '*' => array(),
  
  'id' => array(
    'types' => '_not_null|_primary_key|_int(11)|_auto_increment',
    ),
  'email' => array(
    'types' => '_varchar(60)|_not_null',
    ),
  'business_size' => array(
        '_enum' => array(
            'small',
            'medium',
            'large',
            'xlarge',
            'xxlarge',
        ),
        'types' => '_null|_enum',
        ),
);
 
/* End of file users.php */
/* Location: .app/schemas/users.php */
```

Let's write a schema function for enum type.

```php
<?php 
$users = array(
  '*' => array(),
  
  'id' => array(
    'types' => '_not_null|_primary_key|_int(11)|_auto_increment',
    ),
  'email' => array(
    'types' => '_varchar(60)|_not_null',
    ),
  'business_size' => array(
    '_enum' => array(
            'small',
            'medium',
            'large',
            'xlarge',
            'xxlarge',
        ),
    'types' => '_null|_enum',
    ),
   'func' => function() {
        $options = array(
            'small'   => '1 employee',
            'medium'  => '2 - 10 employees',
            'large'   => '11 - 25 employees',
            'xlarge'  => '26 - 75 employees',
            'xxlarge' => 'More than 75 employees',
        );
        $business_sizes = getSchema('users')['business_size']['_enum'];
        $sizes = array();
        foreach($business_sizes as $val)
        {
            $sizes[$val] = $options[$val];
        }
        return $sizes;
    },
);
```

```php
<?php
echo $this->form->dropdown('business_size', '@getSchema.users.business_size.func', 'xlarge');
```

Adding custom options.

```php
<?php
echo $this->form->dropdown('business_size', array(array('' => 'Please specify a field .. '), '@getSchema.users.business_size.func'));
```


### Array Functions

Also you can build your array closure functions.

```php
  'business_size' => array(
        '_enum' => array(
            'small',
            'medium',
            'large',
            'xlarge',
            'xxlarge',
        ),
        'types' => '_null|_enum',
        ),
      'func' => array(
        'all' => function() {
            $options = array(
                'small'   => '1 employee',
                'medium'  => '2 - 10 employees',
                'large'   => '11 - 25 employees',
                'xlarge'  => '26 - 75 employees',
                'xxlarge' => 'More than 75 employees',
            );
            $business_sizes = getSchema('users')['business_size']['_enum'];
            $sizes = array();
            foreach($business_sizes as $val)
            {
                $sizes[$val] = $options[$val];
            }
            return $sizes;
        },
        'list' => function() {
            $business_sizes = getSchema('users')['business_size']['_enum']['high'];            
            $sizes = $business_sizes();

            unset($sizes['xlarge']);
            unset($sizes['xxlarge']);

            return $sizes;
        },
      )
```

```php
<?php
echo $this->form->dropdown('business_size', '@getSchema.users.business_size.func.list', 'medium');
```

#### $this->form->fieldset()

Lets you generate fieldset/legend fields.

```php
echo $this->form->fieldset('Address Information');
echo "<p>fieldset content here</p>\n";
echo $this->form->fieldsetClose();

// Produces
<fieldset>
<legend>Address Information</legend>
<p>form content here</p>
</fieldset>
```

#### $this->form->fieldsetClose()

Produces a closing <b>fieldset</b> tag. The only advantage of using this function is it permits you to pass data to it which will be added below the tag. For example:

```php
$string = "</div></div>";

echo $this->form->fieldsetClose($string);

// Would produce:
</fieldset>
</div></div>
```

As with other functions, if you would like the tag to contain additional data, like JavaScript, you can pass it as a string in the fourth parameter:

```php
$js = 'onClick="someFunction()"';

 echo $this->form->checkbox('newsletter', 'accept', true, $js)
```

#### $this->form->radio()

This function is identical in all respects to the <dfn>$this->form->checkbox()</dfn> function above except that is sets it as a "radio" type.

#### $this->form->submit()

Lets you generate a standard submit button. Simple example:

```php
echo $this->form->submit('mysubmit', 'Submit Post!');

// Would produce:

<input type="submit" name="mysubmit" value="Submit Post!" />
```

#### $this->form->reset()

Lets you generate a standard reset button. Use is identical to <dfn>$this->form->submit()</dfn>.

#### $this->form->button()

Lets you generate a standard button element. You can minimally pass the button name and content in the first and second parameter:

```php
echo $this->form->button('name','content');

// Would produce
<button name="name" type="button">Content</button> 
```

Or you can pass an associative array containing any data you wish your form to contain: 

```php
$data = array(
    'name' => 'button',
    'id' => 'button',
    'value' => 'true',
    'type' => 'reset',
    'content' => 'Reset'
);

echo $this->form->button($data);

// Would produce:
<button name="button" id="button" value="true" type="reset">Reset</button>  
```

If you would like your form to contain some additional data, like JavaScript, you can pass it as a string in the third parameter: 

```php
$js = 'onClick="someFunction()"';
echo $this->form->button('mybutton', 'Click Me', $js);
```

#### $this->form->close()

Produces a closing tag. The only advantage of using this function is it permits you to pass data to it which will be added below the tag. For example:

```php
$string = "</div></div>";

echo $this->form->close($string);

// Would produce:

</form>
</div></div>
```

#### $this->form->getErrorString($prefix = '', $suffix = '')

Returns all validation errors of Validator Class. Using prefix and suffix parameters you can use custom html tags.

```php
echo $this->form->getErrorString($prefix = '<div class="notification">' , $suffix = '</div>');
```

#### $this->form->message($model, $formMsg = '')

Returns a heading message at top of the current form using the <b>Odm object</b>.

```php
echo $this->form->message($model, $formMsg = '')

// Output <div class="notification error">There are some errors in the form fields !</div>
```

Using optional second parameter you can provide custom errors or success messages otherwise it returns to validation errors of Validator Class.

#### $this->form->prep()

Allows you to safely use HTML and characters such as quotes within form elements without breaking out of the form. Consider this example:

```php
$string = 'Here is a string containing <strong>"quoted"</strong> text.';
<input type="text" name="myform" value="$string" />
```

Since the above string contains a set of quotes it will cause the form to break. The form\prep function converts HTML so that it can be used safely:

```php
<input type="text" name="myform" value="<?php echo $this->form->prep($string); ?>" />
```

**Note:** If you use any of the form helper functions listed in this page the form values will be prepped automatically, so there is no need to call this function. Use it only if you are creating your own form elements.

#### $this->form->setValue('field', 'default value')

Permits you to set the value of an input form or textarea. You must supply the field name via the first parameter of the function. The second (optional) parameter allows you to set a default value for the form. Example:

```php
<input type="text" name="quantity" value="<?php echo $this->form->setValue('quantity', '0'); ?>" size="50" />
```

Setting database row values

```php
<input type="text" name="quantity" value="<?php echo $this->form->setValue('user_email', $row->user_email); ?>" size="50" />
```

The above form will show "0" when loaded for the first time.

#### $this->form->setSelect()

If you use a select menu, this function permits you to display the menu item that was selected. The first parameter must contain the name of the select menu, the second parameter must contain the value of each item, and the third (optional) parameter lets you set an item as the default (use boolean true/false).

Example:

```php
<select name="myselect">
<option value="one" <?php echo  $this->form->setSelect('myselect', 'one', true); ?> >One</option>
<option value="two" <?php echo  $this->form->setSelect('myselect', 'two'); ?> >Two</option>
<option value="three" <?php echo  $this->form->setSelect('myselect', 'three'); ?> >Three</option>
</select>
```

#### $this->form->setCheckbox()

Permits you to display a checkbox in the state it was submitted. The first parameter must contain the name of the checkbox, the second parameter must contain its value, and the third (optional) parameter lets you set an item as the default (use boolean true/false). Example:

```php
<input type="checkbox" name="mycheck" value="1" <?php echo $this->form->setCheckbox('mycheck', '1'); ?> /><br />
<input type="checkbox" name="mycheck" value="2" <?php echo $this->form->setCheckbox('mycheck', '2'); ?> />
```

#### $this->form->setRadio()

Permits you to display radio buttons in the state they were submitted. This function is identical to the <b>setCheckbox()</b> function above.

```php
<input type="radio" name="myradio" value="1" <?php echo  $this->form->setRadio('myradio', '1', true); ?> />
<input type="radio" name="myradio" value="2" <?php echo  $this->form->setRadio('myradio', '2'); ?> />
```

#### $this->form->addBr();

Add html (break) using your default form template.

```php
// gives <div style="padding:10px;">&nbsp;</div>
```

#### $this->form->validatorGetInstance()

Gets the Validator Class instance.

#### $this->form->setNotice($message, ERROR);

Sets a flash notice for the general form errors.

<b>Multi Usage Example</b>

```php
$this->form->setNotice('Test Message 1', ERROR);
$this->form->setNotice('Test Message 2', ERROR);
$this->form->setNotice('Test Message 3', INFO);
$this->form->setNotice('Test Message 4', SUCCESS);

$this->form->getNotice();   // get all messages

// output
<div class="notification error">Test Message 1</div>
<div class="notification error">Test Message 2</div>
<div class="notification error">Test Message 3</div>
```

#### $this->form->getNotice($key OR null);

Ges the stored notification message(s) from current session. If you don't provide a key you can get all notices.
## Form Class

The Form Class file contains functions that assist in working with forms.

### Initializing the Class

-------

```php
new Form;
$this->form->method();
```

The following functions are available:

#### $this->form->func('callback_function', function(){});

Creates a callback function for form validations.

```php

```

#### $this->form->open()

Creates an opening form tag with a base URL <b>built from your config preferences</b>. It will optionally let you add form attributes and hidden input fields.

The main benefit of using this tag rather than hard coding your own HTML is that it permits your site to be more portable in the event your URLs ever change.

Here's a simple example:

```php
echo $this->form->open('email/send');
```

The above example would create a form that points to your base URL plus the "email/send" URI segments, like this:

```php
<form method="post" action="http:/example.com/index.php/email/send" />
```

<b>Adding Attributes</b>

Attributes can be added by passing an associative array to the second parameter, like this:

```php
$attributes = array('class' => 'email', 'id' => 'myform');

echo $this->form->open('email/send', $attributes);
```

The above example would create a form similar to this:

```php
<form method="post" action="http:/example.com/index.php/email/send"  class="email"  id="myform" />
```

<b>Adding Hidden Input Fields</b>

Hidden fields can be added by passing an associative array to the third parameter, like this:

```php
$hidden = array('username' => 'Joe', 'member_id' => '234');

echo $this->form->open('email/send', '', $hidden);
```

The above example would create a form similar to this:

```php
<form method="post" action="http:/example.com/index.php/email/send">
<input type="hidden" name="username" value="Joe" />
<input type="hidden" name="member_id" value="234" />
```

#### $this->form->openMultipart()

This function is absolutely identical to the $this->form->open() tag above except that it adds a multipart attribute, which is necessary if you would like to use the form to upload files with.

#### $this->form->hidden('name', 'value' , $attributes = '')

Lets you generate hidden input fields. You can either submit a name/value string to create one field:

```php
$this->form->hidden('username', 'johndoe',  $attr = " id='username' " );

// Would produce:
<input type="hidden" name="username" value="johndoe" id='username'  />
```

Or you can submit an associative array to create multiple fields:

```php
$data = array(
              'name'  => 'John Doe',
              'email' => 'john@example.com',
              'url'   => 'http://example.com'
            );

echo $this->form->hidden($data);

// Would produce:

<input type="hidden" name="name" value="John Doe" />
<input type="hidden" name="email" value="john@example.com" />
<input type="hidden" name="url" value="http://example.com" />
```

#### $this->form->input('name', 'value',$attributes = '')

Lets you generate a standard text input field. You can minimally pass the field name and value in the first and second parameter:

```php
echo $this->form->input('username', 'johndoe', $attributes = '');
```

Or you can pass an associative array containing any data you wish your form to contain:

```php
$data = array(
              'name'        => 'username',
              'id'          => 'username',
              'value'       => 'johndoe',
              'maxlength'   => '100',
              'size'        => '50',
              'style'       => 'width:50%',
            );

echo $this->form->input($data);

// Would produce:

<input type="text" name="username" id="username" value="johndoe" maxlength="100" size="50" style="width:50%" />
```

If you would like your form to contain some additional data, like Javascript, you can pass it as a string in the third parameter:

```php
$js = 'onclick="someFunction()"';

echo $this->form->input('username', 'johndoe', $js);
```

#### $this->form->password()

This function is identical in all respects to the <dfn>$this->form->input()</dfn> function above except that it sets a "password" type.

#### $this->form->upload()

This function is identical in all respects to the <dfn>$this->form->input()</dfn> function above except that it sets a "file" type, allowing it to be used to upload files.

#### $this->form->textarea()

This function is identical in all respects to the <dfn>$this->form->input()</dfn> function above except that it generates a "textarea" type. Note: Instead of the "maxlength" and "size" attributes in the above example, you will specify "rows" and "cols".

#### $this->form->dropdown()

Lets you create a standard drop-down field. The first parameter will contain the name of the field, the second parameter will contain an associative array of options, and the third parameter will contain the value you wish to be selected. You can also pass an array of multiple items through the third parameter, and Obullo will create a multiple select for you. Example:

```php
$options = array(
                  'small'  => 'Small Shirt',
                  'med'    => 'Medium Shirt',
                  'large'   => 'Large Shirt',
                  'xlarge' => 'Extra Large Shirt',
                );

$shirtsOnSale = array('small', 'large');

echo $this->form->dropdown('shirts', $options, 'large');

// Would produce:

<select name="shirts">
<option value="small">Small Shirt</option>
<option value="med">Medium Shirt</option>
<option value="large" selected="selected">Large Shirt</option>
<option value="xlarge">Extra Large Shirt</option>
</select>

echo $this->form->dropdown('shirts', $options, shirtsOnSale);

// Would produce:

<select name="shirts" multiple="multiple">
<option value="small" selected="selected">Small Shirt</option>
<option value="med">Medium Shirt</option>
<option value="large" selected="selected">Large Shirt</option>
<option value="xlarge">Extra Large Shirt</option>
</select>
```

If you would like the opening <b>select</b> to contain additional data, like an id attribute or JavaScript, you can pass it as a string in the fourth parameter:

```php
$js = 'id="shirts" onChange="someFunction();"';

echo $this->form->dropdown('shirts', $options, 'large', $js);
```

If the array passed as $options is a multidimensional array, $this->form->dropdown() will produce an with the array key as the label.

#### $this->form->multiSelect()

Lets you create a standard multiselect field. The first parameter will contain the name of the field, the second parameter will contain an associative array of options, and the third parameter will contain the value or values you wish to be selected. The parameter usage is identical to using <kbd>$this->form->dropdown()</kbd> above, except of course that the name of the field will need to use POST array syntax, e.g. <kbd>foo[]</kbd>.

#### $this->form->fieldset()

Lets you generate fieldset/legend fields.

```php
echo $this->form->fieldset('Address Information');
echo "<p>fieldset content here</p>\n";
echo $this->form->fieldsetClose();

// Produces
<fieldset>
<legend>Address Information</legend>
<p>form content here</p>
</fieldset>
```

#### $this->form->fieldsetClose()

Produces a closing <b>fieldset</b> tag. The only advantage to using this function is it permits you to pass data to it which will be added below the tag. For example:

```php
$string = "</div></div>";

echo $this->form->fieldsetClose($string);

// Would produce:
</fieldset>
</div></div>
```

As with other functions, if you would like the tag to contain additional data, like JavaScript, you can pass it as a string in the fourth parameter:

```php
$js = 'onClick="someFunction()"';

 echo $this->form->checkbox('newsletter', 'accept', true, $js)
```

#### $this->form->radio()

This function is identical in all respects to the <dfn>$this->form->checkbox()</dfn> function above except that is sets it as a "radio" type.

#### $this->form->submit()

Lets you generate a standard submit button. Simple example:

```php
echo $this->form->submit('mysubmit', 'Submit Post!');

// Would produce:

<input type="submit" name="mysubmit" value="Submit Post!" />
```

#### $this->form->reset()

Lets you generate a standard reset button. Use is identical to <dfn>$this->form->submit()</dfn>.

#### $this->form->button()

Lets you generate a standard button element. You can minimally pass the button name and content in the first and second parameter:

```php
echo $this->form->button('name','content');

// Would produce
<button name="name" type="button">Content</button> 
```

Or you can pass an associative array containing any data you wish your form to contain: 

```php
$data = array(
    'name' => 'button',
    'id' => 'button',
    'value' => 'true',
    'type' => 'reset',
    'content' => 'Reset'
);

echo $this->form->button($data);

// Would produce:
<button name="button" id="button" value="true" type="reset">Reset</button>  
```

If you would like your form to contain some additional data, like JavaScript, you can pass it as a string in the third parameter: 

```php
$js = 'onClick="someFunction()"';
echo $this->form->button('mybutton', 'Click Me', $js);
```

#### $this->form->close()

Produces a closing tag. The only advantage of using this function is it permits you to pass data to it which will be added below the tag. For example:

```php
$string = "</div></div>";

echo $this->form->close($string);

// Would produce:

</form>
</div></div>
```

#### $this->form->getErrorString($prefix = '', $suffix = '')

Returns all validation errors of Validator Class. Using prefix and suffix parameters you can use custom html tags.

```php
echo $this->form->getErrorString($prefix = '<div class="notification">' , $suffix = '</div>');
```

Using optional second parameter you can provide custom errors or success messages otherwise it returns to validation errors of Validator Class.

#### $this->form->prep()

Allows you to safely use HTML and characters such as quotes within form elements without breaking out of the form. Consider this example:

```php
$string = 'Here is a string containing <strong>"quoted"</strong> text.';
<input type="text" name="myform" value="$string" />
```

Since the above string contains a set of quotes it will cause the form to break. The form\prep function converts HTML so that it can be used safely:

```php
<input type="text" name="myform" value="<?php echo $this->form->prep($string); ?>" />
```

**Note:** If you use any of the form helper functions listed in this page the form values will be prepped automatically, so there is no need to call this function. Use it only if you are creating your own form elements.

#### $this->form->setValue()

Permits you to set the value of an input form or textarea. You must supply the field name via the first parameter of the function. The second (optional) parameter allows you to set a default value for the form. Example:

```php
<input type="text" name="quantity" value="<?php echo $this->form->setValue('quantity', '0'); ?>" size="50" />
```

The above form will show "0" when loaded for the first time.

#### $this->form->setSelect()

If you use a select menu, this function permits you to display the menu item that was selected. The first parameter must contain the name of the select menu, the second parameter must contain the value of each item, and the third (optional) parameter lets you set an item as the default (use boolean true/false).

Example:

```php
<select name="myselect">
<option value="one" <?php echo  $this->form->setSelect('myselect', 'one', true); ?> >One</option>
<option value="two" <?php echo  $this->form->setSelect('myselect', 'two'); ?> >Two</option>
<option value="three" <?php echo  $this->form->setSelect('myselect', 'three'); ?> >Three</option>
</select>
```

#### $this->form->setCheckbox()

Permits you to display a checkbox in the state it was submitted. The first parameter must contain the name of the checkbox, the second parameter must contain its value, and the third (optional) parameter lets you set an item as the default (use boolean true/false). Example:

```php
<input type="checkbox" name="mycheck" value="1" <?php echo $this->form->setCheckbox('mycheck', '1'); ?> /><br />
<input type="checkbox" name="mycheck" value="2" <?php echo $this->form->setCheckbox('mycheck', '2'); ?> />
```

#### $this->form->setRadio()

Permits you to display radio buttons in the state they were submitted. This function is identical to the <b>setCheckbox()</b> function above.

```php
<input type="radio" name="myradio" value="1" <?php echo  $this->form->setRadio('myradio', '1', true); ?> />
<input type="radio" name="myradio" value="2" <?php echo  $this->form->setRadio('myradio', '2'); ?> />
```

#### $this->form->addBr();

Add html (break) using your default form template.

```php
// gives <div style="padding:10px;">&nbsp;</div>
```

#### $this->form->func('callback_functionname', function(){});

Sets a custom callback function to form validation rules.

#### $this->form->setRules($field, $rules = '');

Sets form validation rules.

#### $this->form->isValid();

Runs the form validation using validator .

#### $this->form->setError(mixed $field, string $error);

Set error(s) to validator object you can set string or array.

```php
$this->form->setError('days', 'Day data is empty');
$this->form->setError('months', 'Month data is empty');
```

Using array

```php

$errors['days']   = 'Day data is empty';
$errors['months'] = 'Month data is empty';

$this->form->setError($errors);
```

#### $this->form->setNotice($message, ERROR);

Sets a flash notice for the general form errors.

<b>Multi Usage Example</b>

```php
$this->form->setNotice('Test Message 1', ERROR, 'test'); // Specifiy a key ( Optionally )
$this->form->setNotice('Test Message 2', ERROR);
$this->form->setNotice('Test Message 3', INFO);
$this->form->setNotice('Test Message 4', SUCCESS);

$this->form->getNotice('error','test'); // get one notice with your key
$this->form->getNotice();               // get all messages

// output specific key
<div class="notification error">Test Message 1</div>

// output all errors without any key
<div class="notification error">Test Message 1</div>
<div class="notification error">Test Message 2</div>
<div class="notification info">Test Message 3</div>
<div class="notification success">Test Message 4</div>
```
#### $this->form->keepNotice($key);
```php
$this->form->setNotice('Test Message', ERROR,'test');

$this->form->keepNotice();              // protect the specific key
$this->form->getNotice('error','test'); // get specific key

// output
<div class="notification error">Test Message 1</div>
```
#### $this->form->getNotice($key or Null);

Gets the stored notification message(s) from current session.

#### And all Validator Functions Available in the Form Class

All Validator functions are available in form class because if a function is not available it is called from the Validator class by php _call magic method.