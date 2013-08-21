## Xss Helper

The Security Helper file contains security related functions.

### Loading this Helper

------

This helper is loaded using the following code:

```php
new xss\start();
```

### Security Filtering

------

The security filtering function is called automatically when a new [controller](/docs/general/controllers) is invoked. It does the following:

<ul>
   <li>Destroys the global GET array. Since Obullo does not utilize GET strings, there is no reason to allow it.</li>
   <li>Destroys all global variables in the event register_globals is turned on.</li>
    <li>Filters the POST/COOKIE array keys, permitting only alpha-numeric (and a few other) characters.</li>
    <li>Provides XSS (Cross-site Scripting Hacks) filtering. This can be enabled globally, or upon request.</li>
    <li>Standardizes newline characters to \n</li>
</ul>

### XSS Filtering

-------

Obullo comes with a Cross Site Scripting Hack prevention filter which can either run automatically to filter all POST and COOKIE data that is encountered, or you can run it on a per item basis. By default it does not run globally since it requires a bit of processing overhead, and since you may <b>not</b> need it in all cases.

The XSS filter looks for commonly used techniques to trigger Javascript or other types of code that attempt to hijack cookies or do other malicious things. If anything disallowed is encountered it is rendered safe by converting the data to character entities.

<b>Note:</b> This function should only be used to deal with data upon submission. It's not something that should be used for general runtime processing since it requires a fair amount of processing overhead.

To filter data through the XSS filter use this function:

#### clean()

Here is a usage example:

```php
$data = xss\clean($data);
```

If you want the filter to run automatically every time it encounters POST or COOKIE data you can enable it by opening your <samp>app/config/config.php</samp> file and setting this:

```php
$config['global_xss_filtering'] = TRUE;
```

**Tip:** If you use the *form validation class*, it gives you the option of XSS filtering as well.

An optional second parameter, <dfn>is_image</dfn>, allows this function to be used to test images for potential XSS attacks, useful for file upload security. When this second parameter is set to TRUE, instead of returning an altered string, the function returns TRUE if the image is safe, and FALSE if it contained potentially malicious information that a browser may attempt to execute.

```php
if (xss\clean($file, TRUE) === FALSE)
{
    // file failed the XSS test
}
```

#### stripImageTags()

This is a security function that will strip image tags from a string. It leaves the image URL as plain text.

```php
$string = xss\stripImageTags($string);
```
#### encodePhpTags()

This is a security function that converts PHP tags to entities. Note: If you use the XSS filtering function it does this automatically.

```php
$string = xss\encodePhpTags($string);
```

#### Cross-site request forgery (CSRF)

You can enable csrf protection by opening your <samp>app/config/config.php</samp> file and setting this:

```php
$config['csrf_protection'] = TRUE;
```
If you use the [form helper](/ob/form/releases/0.0.1/) the <var>form\open()</var> function will automatically insert a hidden csrf field in your forms.