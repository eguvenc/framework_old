<?php

/*
| -------------------------------------------------------------------
| Form Package Configuration
| -------------------------------------------------------------------
| This file specifies form templates that used by form && Uform
| packages.
|
| -------------------------------------------------------------------
| Prototype
| -------------------------------------------------------------------
| 
| $form['templates']['name'] = array(
|	 	'formClass' => '_formElement>',
|		'button' => '<div class="_buttonElement">%s</span>',
| );
|
*/
$form = array(

	// Global template switch.
	'use_template' => false,

	// Form Templates
	'templates' => array(
		'default' => array(
			'formClass' => '_formElement',
			'button' => '<div class="_buttonElement">%s</span>',
			'checkbox' => '<span class="_checkboxElement">%s</span>',
			'dropdown' => '<span class="_dropdownElement">%s</span>',
			'fieldsetOpen' => '<span class="_fieldsetElement">',
			'fieldsetClose' => '</span>',
			'hidden' => '<span class="_hiddenElement">%s</span>',
			'label' => '<span class="_labelElement">%s</span>',
			'password' => '<span class="_passwordElement">%s</span>',
			'radio' => '<span class="_radioElement">%s</span>',
			'reset' => '<span class="_resetElement">%s</span>',
			'submit' => '<span class="_submitElement">%s</span>',
			'text' => '<span class="_textElement">%s</span>',
			'textarea' => '<span class="_textareaElement">%s</span>',
			'file' => '<span class="_fileElement">%s</span>',
			'addbr' => '<div style="height:%dpx;margin:0;padding:0;">%s</div>'
		),
	),

	// Notification Errors ( Customize form input and notification errors. )
	'errors' => array(
		'error' => '<div class="_inputError">%s</div>',
		'errorMessage' => '<div class="notification error">%s</div>',
		'success' => '<div class="notification success">%s</div>'
	)
);


/* End of file form.php */
/* Location: .app/config/form.php */