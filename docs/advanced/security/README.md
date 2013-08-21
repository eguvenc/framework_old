## Security<a name="security"></a>

### Security

------

This page describes some "best practices" regarding web security, and details Obullo's internal security features.

### URI Security

------

Obullo is fairly restrictive regarding which characters it allows in your URI strings in order to help minimize the possibility that malicious data can be passed to your application. URIs may only contain the following:

<ul>
    <li>Alpha-numeric text</li>
    <li>Tilde: ~</li>
    <li>Period: .</li>
    <li>Colon: :</li>
    <li>Underscore: _</li>
    <li>Dash: -</li></ul>

### GET, POST, and COOKIE Data

------

GET data is simply disallowed by Obullo since the system utilizes URI segments rather than traditional URL query strings (unless you have the query string option enabled in your config file). The global GET array is <b>unset</b> by the Input class during system initialization.

### Register_globals

------

During system initialization all global variables are unset, except those found in the $_POST and $_COOKIE arrays. The unsetting routine is effectively the same as register_globals = off.

### magic_quotes_runtime

------

The magic_quotes_runtime directive is turned off during system initialization so that you don't have to remove slashes when retrieving data from your database.

### Best Practices

------

Before accepting any data into your application, whether it be POST data from a form submission, COOKIE data, URI data, XML-RPC data, or even data from the SERVER array, you are encouraged to practice this three step approach:

<ol>
    <li>Filter the data as if it were tainted.</li>
    <li>Validate the data to ensure it conforms to the correct type, length, size, etc. (sometimes this step can replace step one)</li>
    <li>Escape the data before submitting it into your database.</li></ol>

Obullo provides the following functions to assist in this process:

<ul>
    <li><h3>XSS Filtering</h3></li>
<hr>

    Obullo comes with a Cross Site Scripting filter. This filter looks for commonly used techniques to embed malicious Javascript into your data, or other types of code that attempt to hijack cookies or do other malicious things. The XSS Filter is described <a href="/docs/packages/#security-helpers">here</a>.
    <li><h3>Validate the data</h3></li>
    <hr>

    Obullo has a <a href="/ob/validator/releases/0.0.1/#validator-class">Validator Class</a> that assists you in validating, filtering, and prepping your data.
    <li><h3>Escape all data before database insertion</h3></li>
        <hr>
    Never insert information into your database without escaping it. Please see the section that discusses <a href="/docs/database/#running-and-escaping-queries">running queries</a> for more information.</ul>
