## Email Class


Obullo's robust Email Class supports the following features:

  
- Multiple Protocols: Mail, Sendmail, and SMTP
- Multiple recipients
- CC and BCCs
- HTML or Plaintext email
- Attachments
- Word wrapping
- Priorities
- BCC Batch Mode, enabling large email lists to be broken into small BCC batches.
- Email Debugging tools


### Sending Email

------

Sending email is not only simple, but you can configure it on the fly or set your preferences in a config file.
Here is a basic example demonstrating how you might send email. Note: This example assumes you are sending the email from one of your controllers.

```php
new Email();

$this->email->from('your@example.com', 'Your Name');
$this->email->to('someone@example.com');
$this->email->cc('another@another-example.com');
$this->email->bcc('them@their-example.com');

$this->email->subject('Email Test');
$this->email->message('Testing the email class.');

$this->email->send();

echo $this->email->print_debugger();
```

### Grabbing the Instance

------

Also using new Email(false); boolean you can grab the instance of Obullo libraries,"$this->email->method()" will not available in the controller.

```php
$email = new Email(false);

$email->method();
```

### Setting Email Preferences

------

There are 17 different preferences available to tailor how your email messages are sent. You can either set them manually as described here, or automatically via preferences stored in your config file, described below:

Preferences are set by passing an array of preference values to the email <dfn>init</dfn> function. Here is an example of how you might set some preferences:

```php
$config['protocol'] = 'sendmail';
$config['mailpath'] = '/usr/sbin/sendmail';
$config['charset'] = 'iso-8859-1';
$config['wordwrap'] = TRUE;

$this->email->init($config);
```

<b>Note:</b> Most of the preferences have default values that will be used if you do not set them.

### Setting Email Preferences in a Config File

If you prefer not to set preferences using the above method, you can instead put them into a config file. Simply create a new file called the <var>email.php</var>, add the <var>$config</var> array in that file. Then save the file at <var>config/email.php</var> and it will be used automatically. You will NOT need to use the <dfn>$email->init()</dfn> function if you save your preferences in a config file.

### Email Preferences

------

The following is a list of all the preferences that can be set when sending email:

<table>
<thead>
<tr>    
<th>Preference</th><th>Default Value</th><th>Options</th><th>Description</th></tr></thead><tbody>
<tr><td>useragent</td><td>Obullo</td><td>None</td><td>The "user agent".</td></tr>
<tr><td>protocol</td><td>mail</td><td>mail, sendmail, or smtp</td><td>The mail sending protocol.</td></tr>
<tr><td>mailpath</td><td>/usr/sbin/sendmail</td><td>None</td><td>The server path to Sendmail.</td></tr>
<tr><td>smtp_host</td><td>No Default</td><td>None</td><td>SMTP Server Address.</td></tr>
<tr><td>smtp_user</td><td>No Default</td><td>None</td><td>SMTP Username.</td></tr>
<tr><td>smtp_pass</td><td>No Default</td><td>None</td><td>SMTP Password.</td></tr>
<tr><td>smtp_port</td><td>25</td><td>None</td><td>SMTP Port.</td></tr>
<tr><td>smtp_timeout</td><td>5</td><td>None</td><td>SMTP Timeout (in seconds).</td></tr>
<tr><td>wordwrap</td><td>TRUE</td><td>TRUE or FALSE (boolean)</td><td>Enable word-wrap.</td></tr>
<tr><td>wrapchars</td><td>76</td><td></td><td>Character count to wrap at.</td></tr>
<tr><td>mailtype</td><td>text</td><td>text or html</td><td>Type of mail. If you send HTML email you must send it as a complete web page. Make sure you don't have any relative links or relative image paths otherwise they will not work.</td></tr>
<tr><td>charset</td><td>utf-8</td><td></td><td>Character set (utf-8, iso-8859-1, etc.).</td></tr>
<tr><td>validate</td><td>FALSE</td><td>TRUE or FALSE (boolean)</td><td>	Whether to validate the email address.</td></tr>
<tr><td>priority</td><td>3</td><td>1, 2, 3, 4, 5</td><td>Email Priority. 1 = highest. 5 = lowest. 3 = normal.</td></tr>
<tr><td>crlf</td><td>\n</td><td>"\r\n" or "\n" or "\r"</td><td>	Newline character. (Use "\r\n" to comply with RFC 822).</td></tr>
<tr><td>newline</td><td>\n</td><td>"\r\n" or "\n" or "\r"</td><td>Newline character. (Use "\r\n" to comply with RFC 822).</td></tr>
<tr><td>bcc_batch_mode</td><td>FALSE</td><td>TRUE or FALSE (boolean)</td><td>Enable BCC Batch Mode.</td></tr>
<tr><td>bcc_batch_size</td><td>200</td><td>None</td><td>Number of emails in each BCC batch.</td></tr></tbody></table>

### Email Function Reference

------

#### $this->email->from()

Sets the email address and name of the person sending the email:

```php
$this->email->from('you@example.com', 'Your Name');
```

#### $this->email->replyTo()

Sets the reply-to address. If the information is not provided the information in the "from" function is used. Example:

```php
$this->email->replyTo('you@example.com', 'Your Name');
```

#### $this->email->to()

Sets the email address(s) of the recipient(s). Can be a single email, a comma-delimited list or an array:

```php
$this->email->to('someone@example.com'); 
```

```php
$this->email->to('one@example.com, two@example.com, three@example.com'); 
```

```php
$list = array('one@example.com', 'two@example.com', 'three@example.com');
$this->email->to($list);
```

#### $this->email->cc()

Sets the CC email address(s). Just like the "to", can be a single email, a comma-delimited list or an array.

#### $this->email->bcc()

Sets the BCC email address(s). Just like the "to", can be a single email, a comma-delimited list or an array.

#### $this->email->subject()

Sets the email subject:

```php
$this->email->subject('This is my subject');
```

#### $this->email->message()

Sets the email message body:

```php
$this->email->message('This is my message');
```

#### $this->email->setAltMessage()

Sets the alternative email message body:

```php
$this->email->setAltMessage('This is the alternative message');
```

This is an optional message string which can be used if you send HTML formatted email. It lets you specify an alternative message with no HTML formatting which is added to the header string for people who do not accept HTML email. If you do not set your own message Obullo will extract the message from your HTML email and strip the tags.

#### $this->email->clear()

Initializes all the email variables to an empty state. This function is intended for use if you run the email sending function in a loop, permitting the data to be reset between cycles.

```php
$email = new Email(false);

foreach ($list as $name => $address)
{
    $email->clear();

    $email->to($address);
    $email->from('your@example.com');
    $email->subject('Here is your info '.$name);
    $email->message('Hi '.$name.' Here is the info you requested.');
    $email->send();
}
```

If you set the parameter to TRUE any attachments will be cleared as well:

```php
$this->email->clear(TRUE);
```

#### $this->email->send()

The Email sending function. Returns boolean TRUE or FALSE based on success or failure, enabling it to be used conditionally:

```php
if ( ! $this->email->send())
{
    // Generate error
}
```

#### $this->email->attach()

Enables you to send an attachment. Put the file path/name in the first parameter. Note: Use a file path, not a URL. For multiple attachments use the function multiple times. For example:

```php
$this->email->attach('/path/to/photo1.jpg');
$this->email->attach('/path/to/photo2.jpg');
$this->email->attach('/path/to/photo3.jpg');

$this->email->send();
```

#### $this->email->printDebugger()

Returns a string containing any server messages, the email headers, and the email messsage. Useful for debugging.
### Overriding Word Wrapping

------

If you have word wrapping enabled (recommended to comply with RFC 822) and you have a very long link in your email it can get wrapped too, causing it to become un-clickable by the person receiving it. Obullo lets you manually override word wrapping within part of your message like this:

```php
The text of your email that
gets wrapped normally.

{unwrap}http://example.com/a_long_link_that_should_not_be_wrapped.html{/unwrap}

More text that will be
wrapped normally.
```

Place the item you do not want word-wrapped between: <var>{unwrap} {/unwrap}</var>

 