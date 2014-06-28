## Security

This page describes some "best practices" regarding web security, and details framework's internal security features.

**Note:** This class is a <kbd>component</kbd> defined in your package.json. You can <kbd>replace components</kbd> with third-party packages.

### URI Security

------

Framework is fairly restrictive regarding which characters are allowed in your URI strings in order to help minimize the possibility that malicious data can be passed to your application. URIs may only contain the following:

* Alpha-numeric text
* Tilde: ~
* Period: .
* Colon: :
* Underscore: _
* Dash: -

### GET, POST, and COOKIE Data

------

GET data is simply disallowed by framework since the system utilizes URI segments rather than traditional URL query strings (unless you have the query string option enabled in your config file). The global GET array is <b>unset</b> by the Input class during system initialization.

### Best Practices

------

Before accepting any data into your application, whether it is POST data from a form submission, COOKIE data, URI data, XML-RPC data, or even data from the SERVER array, you are encouraged to practice this three step approach:

* Filter the data as if it were tainted.
* Validate the data to ensure it is in the correct type, length, size, etc. (sometimes this step can replace step one)
* Escape the data before submitting it into your database.

Framework provides the following functions to assist in this process:

### XSS Filtering

Framework comes with a Cross Site Scripting filter. This filter looks for commonly used techniques to embed malicious Javascript into your data, or other types of code that attempt to hijack cookies or do other malicious attacks. The XSS Filter is described here <kbd>/docs/packages/xss</kbd>
    
* Validate the data; framework has a Validator Class <kbd>/docs/packages/validator</kbd> that assists you in validating, filtering, and prepping your data.
* If you want a more robust structure use Odm ( Object Data Model ) <kbd>/docs/packages/odm</kbd> Instead of Validator Class, Odm validates and saves the data using your validation schemas.
* Escape all data before database insertion
* Never insert information into your database without escaping it. Please see the section that discusses running queries at <kbd>/obullo/docs/database/running-and-escaping-queries</kbd> for more information.