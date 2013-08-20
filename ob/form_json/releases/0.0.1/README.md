## Form Send Helper

-----

The Form Send Helper file contains functions that assist in working with AJAX forms.

### Loading this Helper

------

This helper is loaded using the following code:

```php
new form_Json\start();
```

The following functions are available:

#### error($model object or string $error)

Creates for [Validation in Model](/docs/advanced/#validation-in-model) response in <b>JSON</b> format.

```php
{"success":FALSE,
"errors":{"usr_username":"The Username field is required.",
"usr_password":"The Password field is required.",
"usr_confirm_password":"The Confirm Password field is required.",
"usr_email":"The Email Address field is required.",
"captcha_answer":"The Security Image field is required."}}
```

Obullo JQuery Form Plugin which is located framework <dfn>/public/js/form/</dfn> folder, parse above the response and show validation errors or success messages to users. Using <b>Firefox</b> [Firebug]("https://getfirebug.com/downloads") extension you can see all Validation Model JSON requests to easily find the application bugs.

If you provide a <b>Message type of string</b> instead of <b>Model Object</b> function will send a general system error instead of validation errors.

```php
echo form_Json\error('System ERROR !');

{"success":FALSE,"errors":{"system_msg":"System ERROR !"}}
```

#### form_Json\success($msg string, $js_alert = FALSE)

This function encodes a success response in json format.

```php
{"success":TRUE,"success_msg":"Form saved successfully !"} 
```

If you provide a second param as TRUE form plugin will do a <b>javascript alert</b> instead of success message.
```php
{"success":TRUE,"alert":"Form saved successfully !"} 
```

#### form_Json\redirect($redirect_url);

This function encodes a redirect response in json format. Form plugin just will parse this reponse and it will do a javascript redirect.

```php
{"success":TRUE,"success_msg":"","redirect":"http:\/\/example.com"}
```

form_Json\forward($forward_url);

This function encodes a forward get/post response in json format. Form plugin just will parse this reponse and it will do a javascript submit to forward url.

```php
{"success":TRUE,"forward_url":"\/payment\/do_pay"} 
```

To well understand this function, forexample Obullo Form Plugin if get the forward command after that <b>changing the form action attribute</b> then do a <b>javascript</b> submit like this.

```php
document.forms[$form_name].submit();
```

#### form_Json\alert($msg);

You can build a simple javascript alert.

```php
{"success":FALSE,"alert":"Alert me !"} 
```

### Ajax Form Plugin Rules

------

If you use Obullo JQuery Form Plugin the following is a list of all the form plugin functions that are available to use

you can control the form plugin functions using the class <b>attribute</b>

```php
<? echo form\open('/test/vm/start/do_post.json', array('method' => 'POST', 'class' => 'hide-form no-top-msg'));?>
```
### Ajax Form Plugin Attributes

<table><thead><tr>
<th>Attribute</th><th>Description</th></tr></thead><tbody>
<tr><td>no-top-msg</td><td>Form plugin default show a top message warn to user that you can disable this functionality using as class attribute in your form.</td></tr>
<tr><td>no-ajax</td><td>If you disable to ajax post you can use no-ajax class attribute, when the user click to submit button form will do a native post request instead of ajax.</td></tr>
<tr><td>hide-form</td><td>If the form successfully posted with no errors you can hide the form area and you can show the users just success message.</td></tr></tbody></table>