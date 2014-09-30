## Stringo Class

-------

### Initializing the Class

-------

```php
new Stringo();
$this->stringo->method();
```

The following functions are available:

#### $this->stringo->random()

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
echo $this->stringo->random('alnum', 16);
```

#### $this->stringo->alternator()

Allows two or more items to be alternated between, when cycling through a loop. Example:

```php
for ($i = 0; $i < 10; $i++)
{
    echo String::alternator('string one', 'string two');
}
```

You can add as many parameters as you want, and with each iteration of your loop the next item will be returned.

```php
for ($i = 0; $i < 10; $i++)
{
    echo $this->stringo->alternator('one', 'two', 'three', 'four', 'five');
}
```

**Note:** To use multiple separate calls to this function simply call the function with no arguments to re-initialize.

#### $this->stringo->repeater()

Generates repeating copies of the data you submit. Example:

```php
$string = "\n";
echo $this->stringo->repeater($string, 30);
```

The above would generate 30 newlines.

#### $this->stringo->reduceDoubleSlashes()

Converts double slashes in a string to a single slash, except those found in http://. Example:

```php
$string = "http://example.com//index.php";

echo $this->stringo->reduceDoubleSlashes($string); // results in "http://example.com/index.php"
```

#### $this->stringo->trimSlashes()

Removes any leading/trailing slashes from a string. Example:

```php
$string = "/this/that/theother/";

echo $this->stringo->trimSlashes($string); // results in this/that/theother
```

#### $this->stringo->reduceMultiples()

Reduces multiple instances of a particular character occuring directly after each other. Example:

```php
$string = "Fred, Bill,, Joe, Jimmy";

echo $this->stringo->reduceMultiples($string,","); // results in "Fred, Bill, Joe, Jimmy"
```

The function accepts the following parameters:

```php
$this->stringo->reduceMultiples(string: text to search in, string: character to reduce, boolean: whether to remove the character from the front and end of the string)
```

The first parameter contains the string in which you want to reduce the multiplies. The second parameter contains the character you want to remove. The third parameter is false by default; if set to true it will remove occurences of the character at the beginning and the end of the string. Example:

```php
$string = ",Fred, Bill,, Joe, Jimmy,";

echo $this->stringo->reduceMultiples($string, ", ", true); // results in "Fred, Bill, Joe, Jimmy"
```

#### $this->stringo->quotesToEntities()

Converts single and double quotes into the corresponding HTML entities as string. Example:

```php
$string = "Joe's \"dinner\"";

echo $this->stringo->quotesToEntities($string); // results in "Joe's "dinner""
```

#### $this->stringo->stripQuotes()

Removes single and double quotes from a string. Example:

```php
$string = "Joe's \"dinner\"";

echo $this->stringo->stripQuotes($string); // results in "Joes dinner"
```