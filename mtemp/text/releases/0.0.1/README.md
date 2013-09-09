## Text Helper

The Text Helper file contains functions that assist in working with text.

### Loading this Helper

------

This helper is loaded using the following code:

```php
new text\start();
```

The following functions are available:

#### wordLimiter()

Truncates a string to the number of **words** specified. Example:

```php
$string = "Here is a nice text string consisting of eleven words.";

echo text\wordLimiter($string, 4);

// Returns: Here is a nice
```

The third parameter is an optional suffix added to the string. By default it adds an ellipsis.

#### characterLimiter()

Truncates a string to the number of **characters** specified. It maintains the integrity of words so the character count may be slightly more or less then what you specify. Example:

```php
$string = "Here is a nice text string consisting of eleven words.";

echo text\characterLimiter($string, 20);

// Returns: Here is a nice text string.. 
```

The third parameter is an optional suffix added to the string, if undeclared this helper uses an ellipsis.

#### asciiToEntities()

Converts ASCII values to character entities, including high ASCII and MS Word characters that can cause problems when used in a web page, so that they can be shown consistently regardless of browser settings or stored reliably in a database. There is some dependence on your server's supported character sets, so it may not be 100% reliable in all cases, but for the most part it should correctly identify characters outside the normal range (like accented characters). Example:

```php
$string = text\asciiToEntities($string);
```

#### entitiesToAscii()

This function does the opposite of the previous one; it turns character entities back into ASCII.

#### wordCensor()

Enables you to censor words within a text string. The first parameter will contain the original string. The second will contain an array of words which you disallow. The third (optional) parameter can contain a replacement value for the words. If not specified they are replaced with pound signs: # # # #. Example:

```php
$disallowed = array('darn', 'shucks', 'golly', 'phooey');

echo text\wordCensor($string, $disallowed, 'Beep!');
```

#### highlightCode()

Colorizes a string of code (PHP, HTML, etc.). Example:

```php
$string = text\highlightCode($string);
```

The function uses PHP's highlight_string() function, so the colors used are the ones specified in your php.ini file.

#### highlightPhrase()

Will highlight a phrase within a text string. The first parameter will contain the original string, the second will contain the phrase you wish to highlight. The third and fourth parameters will contain the opening/closing HTML tags you would like the phrase wrapped in. Example:

```php
$string = "Here is a nice text string about nothing in particular.";

echo text\highlightPhrase($string, "nice text", '<span style="color:#990000">', '</span>'); 
```

The above text returns:

Here is a <span style="color:#990000">nice text</span> string about nothing in particular.

#### wordWrap()

Wraps text at the specified **character** count while maintaining complete words. Example:

```php
$string = "Here is a simple string of text that will help us demonstrate this function.";

echo text\wordWrap($string, 25);

// Would produce:

/*
Here is a simple string
of text that will help
us demonstrate this
function
*/
```