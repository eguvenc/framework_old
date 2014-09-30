## Xss Helper

The Xss Helper file contains security related functions.

### Security Filtering

------

It does:

<ul>
    <li>Destroy the global GET array. Since framework does not utilize GET strings, there is no reason to allow it.</li>
    <li>Filter the POST/COOKIE array keys, permitting only alpha-numeric (and a few other) characters.</li>
    <li>Provide XSS (Cross-site Scripting Hacks) filtering. This can be enabled globally, or upon request.</li>
</ul>

### XSS Filtering

-------

Framework comes with a Cross Site Scripting Hack prevention filter which can either run automatically to filter all POST and COOKIE data that is encountered, or you can run it on a per item basis. By default it does not run globally since it requires a bit of processing overhead, and since you may <b>not</b> need it in all cases.

The XSS filter looks for commonly used techniques to trigger Javascript or other types of code that attempt to hijack cookies or do other malicious things. If anything disallowed is encountered it is rendered safe by converting the data to character entities.

**Note:** This function should only be used to deal with data upon submission. It's not something that should be used for general runtime processing since it requires a fair amount of processing overhead.

To filter data through the XSS filter use this function:

#### $this->xss->clean()

Here is a usage example:

```php
$data = $this->xss->clean($data);
```

If you want the filter to run automatically every time it encounters POST or COOKIE data you can enable it by opening your <kbd>app/config/config.php</kbd> file and setting this:

```php
$config['global_xss_filtering'] = true;
```

**Tip:** If you use the *form validation class*, it gives you the option of XSS filtering as well.

An optional second parameter, <kbd>is_image</kbd>, allows this function to be used to test images for potential XSS attacks, useful for file upload security. When this second parameter is set to true, instead of returning an altered string, the function returns true if the image is safe, and false if it contains potentially malicious information that a browser may attempt to execute.

```php
if ($this->xss->clean($file, true) === false)
{
    // file failed the XSS test
}
```

#### $this->xss->stripImageTags()

This is a security function that will strip image tags from a string. It leaves the image URL as plain text.

```php
$string = $this->xss->stripImageTags($string);
```
#### $this->xss->encodePhpTags()

This is a security function that converts PHP tags to entities. Note: If you use the XSS filtering function it does this automatically.

```php
$string = $this->xss->encodePhpTags($string);
```

#### Cross-site request forgery (CSRF)

You can enable csrf protection by opening your <kbd>app/config/debug/config.php</kbd> file and setting this:

```php
$config['csrf_protection'] = true;
```
If you use the form helper <kbd>( /packages/form )</kbd> the <var>$form->open()</var> function will automatically add a hidden csrf field in your forms.
