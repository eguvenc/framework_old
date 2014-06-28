## Utf8 Class

Utf8 Class contains utf8 functions which are the utf8 version of standard Php functions. The class constructor first  checks whether your server supports the <strong>UTF8 natively</strong> if yes the library use the **ICONV** functions.

### Initializing the Class

-------

```php
new Utf8();
$this->utf8->method();
```

Once loaded, the Utf8 object will be available using: $this->utf8->method();

### Function Reference

------

#### $this->utf8->_ltrim($str);

Strips whitespace (or other UTF-8 characters) from the beginning of a string. This is a UTF8-aware version of (http://php.net/ltrim).

#### $this->utf8->_ord($str);

Returns the unicode ordinal for a character. This is a UTF8-aware version of (http://php.net/ord).

#### $this->utf8->_rtrim($str);

Strips whitespace (or other UTF-8 characters) from the end of a string. This is a UTF8-aware version of (http://php.net/rtrim).

#### $this->utf8->_str_ireplace($search = '', $replace = '', $str = '');

Returns a string or an array with all occurrences of search in subject (ignoring case) and replaced with the given replace value. This is a UTF8-aware version of (http://php.net/str_ireplace).

#### $this->utf8->_str_pad($str, $final_str_length, $pad_str = ' ', $pad_type = STR_PAD_RIGHT);

Pads a UTF-8 string to a certain length with another string. This is a UTF8-aware version of (http://php.net/str_pad).

#### $this->utf8->_str_split($str, $split_length = 1);

Converts a UTF-8 string to an array. This is a UTF8-aware version of (http://php.net/str_split).

#### $this->utf8->_strcasecmp($str1, $str2);

Case-insensitive UTF-8 string comparison. This is a UTF8-aware version of (http://php.net/strcasecmp).

#### $this->utf8->_strcspn($str, $mask, $offset = NULL, $length = NULL);

Finds the length of the initial segment not matching the mask. This is a UTF8-aware version of (http://php.net/strcspn).

#### $this->utf8->_stristr($str, $search);

Case-insensitive UTF-8 version of strstr. Returns all of input string from the first occurrence of needle to the end. This is a UTF8-aware version of (http://php.net/stristr).

#### $this->utf8->_strlen($str);

Returns the length of the given string. This is a UTF8-aware version of (http://php.net/strlen).

#### $this->utf8->_strpos($str, $search, $offset = 0);

Finds the position of first occurrence of a UTF-8 string. This is a UTF8-aware version of (http://php.net/strpos).

#### $this->utf8->_strrev($str, $search, $offset = 0);

Reverses a UTF-8 string. This is a UTF8-aware version of (http://php.net/strrev).

#### $this->utf8->_strrpos($str, $search, $offset = 0);

Finds the position of last occurrence of a char in a UTF-8 string. This is a UTF8-aware version of (http://php.net/strrpos).

#### $this->utf8->_strspn($str, $mask, $offset = NULL, $length = NULL);

Finds the length of the initial segment matching mask. This is a UTF8-aware version of (http://php.net/strspn).

#### $this->utf8->_strtolower($str, $locale = 'en-US');

Makes a UTF-8 string lowercase. This is a UTF8-aware version of (http://php.net/strtolower).

#### $this->utf8->_strtoupper($str);

Makes a UTF-8 string uppercase. This is a UTF8-aware version of (http://php.net/strtoupper).

#### $this->utf8->_substr($str, $offset, $length = NULL);

Returns part of a UTF-8 string. This is a UTF8-aware version of (http://php.net/substr).

#### $this->utf8->_substr_replace($str, $replacement, $offset, $length = NULL);

Replaces text within a portion of a UTF-8 string. This is a UTF8-aware version of (http://php.net/substr_replace).

#### $this->utf8->_trim($str);

Strips whitespace (or other UTF-8 characters) of a string. This is a UTF8-aware version of (http://php.net/trim).

#### $this->utf8->_ucfirst($str);

Makes a UTF-8 string's first character uppercase. This is a UTF8-aware version of (http://php.net/ucfirst).

#### $this->utf8->_ucwords($str);

Makes the first character of every word in a UTF-8 string uppercase. This is a UTF8-aware version of (http://php.net/ucwords).

#### $this->utf8->fromUnicode($array);

Takes an array of ints representing the Unicode characters and returns a UTF-8 string.

#### $this->utf8->toAscii($str, $locale = 'tr_TR');

Replaces special/accented UTF-8 characters by ASCII-7 "equivalents". The second parameter use the transliteration table config file which is defined in <kbd>app/i18n/tr_TR</kbd> folder. Default is **en_US**.

```php
echo $this->utf8->toAscii('your-Utf8-string', 'de_DE');
```

#### $this->utf8->toUnicode($array);

Takes an UTF-8 string and returns an array of its representing Unicode characters.