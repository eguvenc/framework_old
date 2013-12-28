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
| $form = array(
|	'templates' => array(
|		'default' => array(
|			'formClass' => '_formElement', .. );
|
*/

$form = array(

	// Settings
	'use_template' => false,
	'get' => new Get,    // Input Object
	'validator' => new Validator, // Validator Object

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

	// Form Notifications
	'notifications' => array(
		'error' => '<div class="_inputError">%s</div>',
		'errorMessage' => '<div class="notification error">%s</div>',
		'successMessage' => '<div class="notification success">%s</div>',
		'infoMessage' => '<div class="notification info">%s</div>',
	)
);

/* End of file form.php */
/* Location: .app/config/form.php */