## Form Helper

-----

The Form Helper file contains functions that assist in working with forms.

### Loading this Helper

------

This helper is loaded using the following code:

new form\start();

The following functions are available:

#### formOpen()

Creates an opening form tag with a base URL <b>built from your config preferences</b>. It will optionally let you add form attributes and hidden input fields.

The main benefit of using this tag rather than hard coding your own HTML is that it permits your site to be more portable in the event your URLs ever change.

Here's a simple example:

```php
echo formOpen('email/send');
```

The above example would create a form that points to your base URL plus the "email/send" URI segments, like this:

```php
<form method="post" action="http:/example.com/index.php/email/send" />
```

<b>Adding Attributes</b>

Attributes can be added by passing an associative array to the second parameter, like this:

```php
$attributes = array('class' => 'email', 'id' => 'myform');

echo formOpen('email/send', $attributes);
```

The above example would create a form similar to this:

```php
<form method="post" action="http:/example.com/index.php/email/send"  class="email"  id="myform" />
```

<b>Adding Hidden Input Fields</b>

Hidden fields can be added by passing an associative array to the third parameter, like this:

```php
$hidden = array('username' => 'Joe', 'member_id' => '234');

echo formOpen('email/send', '', $hidden);
```

The above example would create a form similar to this:

```php
<form method="post" action="http:/example.com/index.php/email/send">
<input type="hidden" name="username" value="Joe" />
<input type="hidden" name="member_id" value="234" />
```

#### formOpenMultipart()

This function is absolutely identical to the form_open() tag above except that it adds a multipart attribute, which is necessary if you would like to use the form to upload files with.

#### formHidden('name', 'value' , $attributes = '')

Lets you generate hidden input fields. You can either submit a name/value string to create one field:

```php
formHidden('username', 'johndoe',  $attr = " id='username' " );

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

echo formHidden($data);

// Would produce:

<input type="hidden" name="name" value="John Doe" />
<input type="hidden" name="email" value="john@example.com" />
<input type="hidden" name="url" value="http://example.com" />
```

#### formInput('name', 'value',$attributes = '')

Lets you generate a standard text input field. You can minimally pass the field name and value in the first and second parameter:

```php
echo form_input('username', 'johndoe', $attributes = '');
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

echo formInput($data);

// Would produce:

<input type="text" name="username" id="username" value="johndoe" maxlength="100" size="50" style="width:50%" />
```

If you would like your form to contain some additional data, like Javascript, you can pass it as a string in the third parameter:

```php
$js = 'onClick="some_function()"';

echo formInput('username', 'johndoe', $js);
```

#### formPassword()

This function is identical in all respects to the <dfn>formInput()</dfn> function above except that is sets it as a "password" type.

#### formUpload()

This function is identical in all respects to the <dfn>formInput()</dfn> function above except that is sets it as a "file" type, allowing it to be used to upload files.

#### formTextarea()

This function is identical in all respects to the <dfn>formInput()</dfn> function above except that it generates a "textarea" type. Note: Instead of the "maxlength" and "size" attributes in the above example, you will instead specify "rows" and "cols".

#### formDropdown()

Lets you create a standard drop-down field. The first parameter will contain the name of the field, the second parameter will contain an associative array of options, and the third parameter will contain the value you wish to be selected. You can also pass an array of multiple items through the third parameter, and Obullo will create a multiple select for you. Example:

```php
$options = array(
                  'small'  => 'Small Shirt',
                  'med'    => 'Medium Shirt',
                  'large'   => 'Large Shirt',
                  'xlarge' => 'Extra Large Shirt',
                );

$shirts_on_sale = array('small', 'large');

echo formDropdown('shirts', $options, 'large');

// Would produce:

<select name="shirts">
<option value="small">Small Shirt</option>
<option value="med">Medium Shirt</option>
<option value="large" selected="selected">Large Shirt</option>
<option value="xlarge">Extra Large Shirt</option>
</select>

echo formDropdown('shirts', $options, $shirts_on_sale);

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
$js = 'id="shirts" onChange="some_function();"';

echo formDropdown('shirts', $options, 'large', $js);
```

If the array passed as $options is a multidimensional array, form_dropdown() will produce an with the array key as the label.

#### formMultiselect()

Lets you create a standard multiselect field. The first parameter will contain the name of the field, the second parameter will contain an associative array of options, and the third parameter will contain the value or values you wish to be selected. The parameter usage is identical to using <kbd>formDropdown()</kbd> above, except of course that the name of the field will need to use POST array syntax, e.g. <kbd>foo[]</kbd>.

#### formFieldset()

Lets you generate fieldset/legend fields.

```php
echo formFieldset('Address Information');
echo "<p>fieldset content here</p>\n";
echo formFieldset_close();

// Produces
<fieldset>
<legend>Address Information</legend>
<p>form content here</p>
</fieldset>
```

#### formFieldsetClose()

Produces a closing <b>fieldset</b> tag. The only advantage to using this function is it permits you to pass data to it which will be added below the tag. For example:

```php
$string = "</div></div>";

echo fieldsetClose($string);

// Would produce:
</fieldset>
</div></div>
```

As with other functions, if you would like the tag to contain additional data, like JavaScript, you can pass it as a string in the fourth parameter:

```php
$js = 'onClick="some_function()"';

 echo formCheckbox('newsletter', 'accept', TRUE, $js)
```

#### formRadio()

This function is identical in all respects to the <dfn>formCheckbox()</dfn> function above except that is sets it as a "radio" type.

#### formSubmit()

Lets you generate a standard submit button. Simple example:

```php
echo formSubmit('mysubmit', 'Submit Post!');

// Would produce:

<input type="submit" name="mysubmit" value="Submit Post!" />
```

#### formReset()

Lets you generate a standard reset button. Use is identical to <dfn>formSubmit()</dfn>.

#### formButton()

Lets you generate a standard button element. You can minimally pass the button name and content in the first and second parameter:

```php
echo formButton('name','content');

// Would produce
<button name="name" type="button">Content</button> 
```

Or you can pass an associative array containing any data you wish your form to contain: 

```php
$data = array(
    'name' => 'button',
    'id' => 'button',
    'value' => 'TRUE',
    'type' => 'reset',
    'content' => 'Reset'
);

echo formButton($data);

// Would produce:
<button name="button" id="button" value="TRUE" type="reset">Reset</button>  
```

If you would like your form to contain some additional data, like JavaScript, you can pass it as a string in the third parameter: 

```php
$js = 'onClick="someFunction()"';
echo formButton('mybutton', 'Click Me', $js);
```

#### formClose()

Produces a closing tag. The only advantage to using this function is it permits you to pass data to it which will be added below the tag. For example:

```php
$string = "</div></div>";

echo formClose($string);

// Would produce:

</form>
</div></div>
```

#### validation_errors($prefix = '', $suffix = '')

Return to all validation errors of Validator Class. Using prefix and suffix parameters you can use custom html tags.

```php
echo validation_errors($prefix = '<div class="notification">' , $suffix = '</div>');
```

#### formMsg($model, $form_msg = '')

Return a heading message at top of the current form using the <b>Validation Model object</b>.

```php
echo formMsg($model, $form_msg = '')

// Output <div class="notification error">There are some errors in the form fields !</div>
```

Using optional second parameter you can provide custom errors or success messages otherwise it returns to validation errors of Validator Class.

#### formPrep()

Allows you to safely use HTML and characters such as quotes within form elements without breaking out of the form. Consider this example:

```php
$string = 'Here is a string containing <strong>"quoted"</strong> text.';
<input type="text" name="myform" value="$string" />
```

Since the above string contains a set of quotes it will cause the form to break. The form_prep function converts HTML so that it can be used safely:

```php
<input type="text" name="myform" value="<?php echo form_prep($string); ?>" />
```

**Note:** If you use any of the form helper functions listed in this page the form values will be prepped automatically, so there is no need to call this function. Use it only if you are creating your own form elements.

#### setValue()

Permits you to set the value of an input form or textarea. You must supply the field name via the first parameter of the function. The second (optional) parameter allows you to set a default value for the form. Example:

```php
<input type="text" name="quantity" value="<?php echo set_value('quantity', '0'); ?>" size="50" />
```

The above form will show "0" when loaded for the first time.

#### setSelect()

If you use a select menu, this function permits you to display the menu item that was selected. The first parameter must contain the name of the select menu, the second parameter must contain the value of each item, and the third (optional) parameter lets you set an item as the default (use boolean TRUE/FALSE).

Example:

```php
<select name="myselect">
<option value="one" <?php echo  set_select('myselect', 'one', TRUE); ?> >One</option>
<option value="two" <?php echo  set_select('myselect', 'two'); ?> >Two</option>
<option value="three" <?php echo  set_select('myselect', 'three'); ?> >Three</option>
</select>
```

#### setCheckbox()

Permits you to display a checkbox in the state it was submitted. The first parameter must contain the name of the checkbox, the second parameter must contain its value, and the third (optional) parameter lets you set an item as the default (use boolean TRUE/FALSE). Example:

```php
<input type="checkbox" name="mycheck" value="1" <?php echo set_checkbox('mycheck', '1'); ?> /><br />
<input type="checkbox" name="mycheck" value="2" <?php echo set_checkbox('mycheck', '2'); ?> />
```

#### setRadio()

Permits you to display radio buttons in the state they were submitted. This function is identical to the <b>set_checkbox()</b> function above.

```php
<input type="radio" name="myradio" value="1" <?php echo  set_radio('myradio', '1', TRUE); ?> />
<input type="radio" name="myradio" value="2" <?php echo  set_radio('myradio', '2'); ?> />
```