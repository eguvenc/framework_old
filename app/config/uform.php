<?php

/*
| -------------------------------------------------------------------
| uFORM
| -------------------------------------------------------------------
| This file specifies form templates that used by package uform.
|
| -------------------------------------------------------------------
| Prototype
| -------------------------------------------------------------------
| 
| $uform['template_name'] = array(
|	 	'open' => '<form action="%s" method="%s" name="%s" class="" />',
|		'close' => '</form>',
| );
|
*/

$uform['default'] = array(
'open' => '<form action="%s" method="%s" name="%s" class="%s _formElement" />',
'close' => '</form>',
'error' => '<div class="inputError">%s</div>',
'errorMessage' => '<div class="notification error">%s</div>',
'errorString' => '<div class="notification error">%s</div>',
'success' => '<div class="notification success">%s</div>',
'button' => '<div class="_buttonElement">%s</div>',
'checkbox' => '<div class="_checkboxElement">%s</div>',
'dropdown' => '<div class="_dropdownElement">%s</div>',
'fieldset' => '<div class="_fieldsetElement">%s</div>',
'hidden' => '<div class="_hiddenElement">%s</div>',
'label' => '<div class="_labelElement">%s</div>',
'password' => '<div class="_passwordElement">%s</div>',
'radio' => '<div class="_radioElement">%s</div>',
'reset' => '<div class="_resetElement">%s</div>',
'submit' => '<div class="_submitElement">%s</div>',
'text' => '<div class="_textElement">%s</div>',
'textarea' => '<div class="_textareaElement">%s</div>',
);


/* End of file uform.php */
/* Location: .app/config/uform.php */