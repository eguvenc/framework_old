## String Helper

### Loading this Helper

------

This helper is loaded using the following code:

```php
new string\start();
```

The following functions are available:

#### random()

Generates a random string based on the type and length you specify. Useful for creating passwords or generating random hashes.

The first parameter specifies the type of string, the second parameter specifies the length. The following choices are available:

* <b>alnum:</b>  Alpha-numeric string with lower and uppercase characters.
* <b>alnum_upper:</b> Alpha-numeric string with **uppercase** characters.
* <b>alnum_lower:</b> Alpha-numeric string with **lowercase** characters.
* <b>numeric:</b>  Numeric string.
* <b>nozero:</b>  Numeric string with no zeros.
* <b>unique:</b>  Encrypted with MD5 and uniqid(). Note: The length parameter is not available for this type. Returns a fixed length 32 character string.

Usage example:

```php
echo string\random('alnum', 16);
```

#### alternator()

Allows two or more items to be alternated between, when cycling through a loop. Example:

```php
for ($i = 0; $i < 10; $i++)
{
    echo string\alternator('string one', 'string two');
}
```

You can add as many parameters as you want, and with each iteration of your loop the next item will be returned.

```php
for ($i = 0; $i < 10; $i++)
{
    echo alternator('one', 'two', 'three', 'four', 'five');
}
```

**Note:** To use multiple separate calls to this function simply call the function with no arguments to re-initialize.

#### repeater()

Generates repeating copies of the data you submit. Example:

```php
$string = "\n";
echo string\repeater($string, 30);
```

The above would generate 30 newlines.

#### reduceDoubleSlashes()

Converts double slashes in a string to a single slash, except those found in http://. Example:

```php
$string = "http://example.com//index.php";

echo string\reduceDoubleSlashes($string); // results in "http://example.com/index.php"
```

#### trim_slashes()

Removes any leading/trailing slashes from a string. Example:

```php
$string = "/this/that/theother/";

echo trimSlashes($string); // results in this/that/theother
```

#### reduceMultiples()

Reduces multiple instances of a particular character occuring directly after each other. Example:

```php
$string = "Fred, Bill,, Joe, Jimmy";

$string = string\reduceMultiples($string,","); // results in "Fred, Bill, Joe, Jimmy"
```

The function accepts the following parameters:

```php
string\reduceMultiples(string: text to search in, string: character to reduce, 

boolean: whether to remove the character from the front and end of the string)
```

The first parameter contains the string in which you want to reduce the multiplies. The second parameter contains the character you want to have reduced. The third parameter is false by default; if set to true it will remove occurences of the character at the beginning and the end of the string. Example:

```php
$string = ",Fred, Bill,, Joe, Jimmy,";

$string = string\reduceMultiples($string, ", ", true); // results in "Fred, Bill, Joe, Jimmy"
```

#### quotes2entities()

Converts single and double quotes in a string to the corresponding HTML entities. Example:

```php
$string = "Joe's \"dinner\"";

$string = string\quotes2entities($string); // results in "Joe's "dinner""
```

#### stripQuotes()

Removes single and double quotes from a string. Example:

```php
$string = "Joe's \"dinner\"";

$string = string\stripQuotes($string); // results in "Joes dinner"
```