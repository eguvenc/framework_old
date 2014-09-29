## Form_Builder Class

This class allows you to create a completely working form without writing any html tag even those which are used to coordinate the form, so you just write code and then the class will automatically generate all HTML tags.
Form_Builder uses the same Obullo helpers for form & inputs so it will not be something new to you.

You just write your php code to generate the form , then just call 'printForm' function where ever you want. The output will be formated by 'div' elements and 'css', which allows you to modify it easly.

<ul>
<li><a href='#ConstructingForm'>Constructing a Form</a></li>
<li><a href='#createForm'>Create a Form</a></li>
<li><a href='#printTheForm'>Print The Form</a></li>
<li><a href='#styling'>Styling Form</a></li>
<li><a href='#validation'>Validation</a></li>
<li><a href='#captcha'>Adding captcha</a></li>
<li><a href='#multiforms'>Creating Multiforms</a></li>
<li><a href='#fullexample'>Full Example</a></li>
</ul>


### How To Use <a name='ConstructingForm'></a>

------

First step is creating new instance of Form_builder class. The constructor accepts 3 parameters, the first one is 'action' attribute of the form, second one is attributes array, the third one is a closure function for adding form elements and rules.


```php
new Form_Builder('/tutorials/hello_form_builder', array('method' => 'post'), function(){
    /* building form statements */
});
$this->form_builder->create('form_identifier');
// validate
$this->form_builder->isValid('form_identifier');
// print form
echo $this->form_builder->printCss();
echo $this->form_builder->printForm('form_identifier');
```


Elements are being added to the form as columns and are grouped into rows, so first you have to add a row then add columns into it. Each column equals an 'input' element.
See the following example :

```php
<?php
new Form_Builder('/tutorials/hello_form_builder', array('method' => 'post'), function(){

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->setClass('class1','class2'); // set a class to row
        $this->addCol(array(
            'label' => 'Email',
            'rules' => 'required|validEmail',
            'input' => $this->input('user_email', $this->setValue('user_email')),
            'attr'  => ' id="emailDIV" class="customEmail" ',
        ));

        $this->addRow();
        $this->setTitle('<h1>Privacy & Policy</h1>'); // set title to row
        $this->addCol(array(
            'label' => 'Policy : ',
            'rules' => 'required|contains(n,y)',
            array(
                'label' => 'Yes', 
                'input' => $this->radio('pp', 'y', $this->setRadio('pp', 'y')) 
            ),
        ));

        $this->addRow();
        $this->addCol(array(
            'label' => '&nbsp;',
            'input' => $this->submit('dopost', 'Do Post', ' id="dopost" '),
        ));
    });
?>
```

First we add a new row '$this->addRow', all the columns added after '$this->addRow' will be grouped in the same row, unless you add another row and start a new group.

<strong>addCol</strong> function accepts an array , this array declare 'label of the column', 'input field' and 'validation rules'.
As we mentioned before that this class is using the same functions of Obullo form package.

For <strong>Radios & Checkboxs</strong> you have to add a label for each checkbox or radio element, & a general label for the column i.e :

```php
<?php
$this->addCol(array(
        'label' => 'Language',
        array( 
            'label' => 'Turkish', 
            'input' => $this->checkbox( 'lang', 'TR', $this->setCheckbox( 'lang' , 'TR' ) ) 
            ) ,
        array( 
            'label' => 'English', 
            'input' => $this->checkbox( 'lang', 'EN', $this->setCheckbox( 'lang', 'EN' ) ) 
            ),
        )
    );
?>
```

### Creating The Form <a name='createForm'></a>

------

After construction the form you need just to call 'create' function and set an identifier for your form.

```php
<?php $this->form_builder->create('test_form_identifier') ?>
```

### Print The Form <a name='printTheForm'></a>

------

After creating the form you just need to decide where to print it and then call '$this->form_builder->printForm(form_identifier)'. Inaddition to get the correct shape of your form you have to 'printCss' in the header.

It accepts one parameter 'form_identifier', the same value used in 'create' function.

```php
/* header code */ 
<?php echo $this->form_builder->printCss() ?>
/* view code */ 
<?php echo $this->form_builder->printForm('test_form_identifier') ?>
```

### Styling Form <a name='styling'></a>

------

Form_Builder uses 'div' as a wrapper for all elements, so the 'row' is a 'division' , as well the column, label and error message, moreover it uses css classes to coordinate the output, these css styles are written in form_builder.css, so this gives you the availability to update the output depending on your needs, however it is recommended to save your changesin a separate css file.
Example from form_builder.css :

```css
.form_builder-div-wrapper
{
  /*--*/
}

.form_builder-row
{
  /*--*/
}

.form_builder-column
{
  /*--*/
}

.form_builder-column input,
.form_builder-column select
{
  /*--*/
}
.form_builder-column input[type='submit']
{
  /*--*/
}

.form_builder-label-wrapper
{
  /*--*/
}
```

You can add custom styles to your form by setting a class for any element you want to customize, FormBuilder allows you to set class to row, column and input.

Setting a class to row can be done like the following example 'while building the form' :

```php 
<?php
    $this->addRow();
    $this->setClass('class1','class2','class_etc_');
 ?>
```

### Validation <a name='validation'></a>

------

After you set the rules for each column , you can run the validation through 'isValid'. This will validate all the fields using native isValid function in validator package of Obullo framework.

It accepts one parameter 'form_identifier', the same value used in 'create' function.

```php
$this->form_builder->isValid('test_form_identifier');
```

### Adding Captcha <a name='captcha'></a>

------

As well 'input' elemets, this can be added through addCol function.
Captcha must be configuared in the config file of Form_Builder.

```php
<?php
    $this->addCol(array(
            'label' => 'Security Image',
            'rules' => 'required',
            'input' => $this->captcha('answer') 
        ));
?>
```

<strong>Captcha configuration :</strong>
<br>
<br>
<strong>hidden_input_name</strong> : is required. Name of the hidden input which holds the value of image id.
<br>
<strong>func</strong> : is required. A closure which assumed to construct a capcha object and return image_url and image_id through an array.

```php 
<?php
    'captcha'   => array(
        'image_template'    => '<img src="%s" />',
        'hidden_input_name' => 'image_id',
        'func' => function(){ 
            new Captcha;

            $this->captcha->setDriver('secure'); 
            /* and more settings */
            $this->captcha->create();

            return array(
                'image_url'             => $this->captcha->getImageUrl(),
                'image_id'              => $this->captcha->getImageId(), // value of hidden_field_name
            );
        }
    )
?>
```

### Creating Multiforms <a name='multiforms'></a>

------

The same procedure of creating a single form.

```php
<?php
/* creating first form */
new Form_Builder('/tutorials/hello_form_builder', array('method' => 'post'), function(){
    /* building form statements */
});

$this->form_builder->create('form_1');

/* creating second form */
new Form_Builder('/tutorials/hello_form_builder', array('method' => 'post'), function(){
    /* building form statements */
});

$this->form_builder->create('fomr_2');

/* validation : */
$this->form_builder->isValid('form_1');
$this->form_builder->isValid('form_2');
/* print forms */
$this->form_builder->printForm('form_1');
$this->form_builder->printForm('form_2');
?>
```


### Full Example <a name='fullexample'></a>

------


```php
<?php
new Form_Builder('/tutorials/hello_form_builder', array('method' => 'post'), function(){

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Email',
            'rules' => 'required|validEmail',
            'input' => $this->input('user_email', $this->setValue('user_email')),
        ));

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Password',
            'rules' => 'required|minLen(6)',
            'input' => $this->password('user_password', $this->setValue('user_password')),
        ));

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Confirm',
            'rules' => 'required|matches(user_password)',
            'input' => $this->password('confirm_password', $this->setValue('confirm_password'), ' id="confirm" ' ),
        ));

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Policy : ',
            'rules' => 'required|contains(n,y)',
            array('label' => 'Yes', 'input' => $this->radio('pp', 'y', $this->setRadio('pp', 'y')) ),
            array('label' => 'No', 'input' => $this->radio('pp', 'n', $this->setRadio('pp', 'n')) ),
        ));
        $this->addCol(array(
                   'label' => 'Country',
                   'input' => $this->dropdown('country', array(1 => 'Germany' , 2 => 'US' , 3 => 'Syria'), $this->setValue('country'), " id='cntry' "),
                   'rules' => 'required|xssClean'
                )
        );

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Languages : ',
            'rules' => 'contains(en,de,ar)',
            array('label' => 'English', 'input' => $this->checkbox('lang[]', 'en', $this->setCheckbox('lang', 'en')) ),
            array('label' => 'Turkish', 'input' => $this->checkbox('lang[]', 'tr', $this->setCheckbox('lang', 'tr')) ),
            array('label' => 'Arabic', 'input' => $this->checkbox('lang[]', 'ar', $this->setCheckbox('lang', 'ar')) ),
        ));

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Security Image',
            'rules' => 'required',
            'input' => $this->captcha('answer')
        ));

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => '&nbsp;',
            'input' => $this->submit('dopost', 'Do Post', ' id="dopost" '),
        ));
    });
?>
```