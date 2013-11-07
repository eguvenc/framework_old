<?php

/*
| -------------------------------------------------------------------
| FORM
| -------------------------------------------------------------------
| This file specifies form templates that used by packages form && Uform.
|
| -------------------------------------------------------------------
| Prototype
| -------------------------------------------------------------------
| 
| $form['template_name'] = array(
|	 	'formClass' => '_formElement>',
|		'error' => '<div class="_inputError">%s</div>',
		'errorMessage' => '<div class="notification error">%s</div>',
| );
|
*/

$form['default'] = array(
'formClass' => '_formElement',
'error' => '<div class="_inputError">%s</div>',
'errorMessage' => '<div class="notification error">%s</div>',
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
'break' => '<div style="padding:10px;">&nbsp;</div>'
);


/* End of file form.php */
/* Location: .app/config/form.php */