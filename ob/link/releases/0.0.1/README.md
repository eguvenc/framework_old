## Link Helper

The Link Helper file contains functions that assist in working with URLs.

Loading this Helper

This helper is loaded using the following code:

```php
new link\start();
```
The following functions are available:

#### mailTo()

Creates a standard HTML email link. Usage example:

```php
echo link\mailTo('me@my-site.com', 'Click Here to Contact Me');

```
As with the [anchor()](/ob/url/releases/0.0.1/) tab , you can set attributes using the third parameter.

#### safeMailTo()

Identical to the above function except it writes an obfuscated version of the mailto tag using ordinal numbers written with JavaScript to help prevent the email address from being harvested by spam bots.

```php
new link\start();

echo link\safeMailTo('me@me.com','me@me.com');  

// Produces javascript safe email link: me@me.com
var l = new Array();
 
l[0]='>';l[1]='a';l[2]='/';l[3]='<';l[4]='|109';l[5]='|111';
l[6]='|99';l[7]='|46';l[8]='|101';l[9]='|109';l[10]='|64';
l[11]='|101';l[12]='|109';l[13]='>';l[14]='"';l[15]='|109';
l[16]='|111';l[17]='|99';l[18]='|46';l[19]='|101';l[20]='|109';
l[21]='|64';l[22]='|101';l[23]='|109';l[24]=':';l[25]='o';
l[26]='t';l[27]='l';l[28]='i';l[29]='a';l[30]='m';l[31]='"';l[32]='=';
l[33]='f';l[34]='e';l[35]='r';l[36]='h';l[37]=' ';l[38]='a';l[39]='<';
```

#### autoLink()

Automatically turns URLs and email addresses contained in a string into links. Example:

```php
$string = link\autoLink($string);
```

The second parameter determines whether URLs and emails are converted or just one or the other. Default behavior is both if the parameter is not specified. Email links are encoded as link\safeMailTo() as shown above.

Converts only URLs:

```php
$string = link\autoLink($string, 'url');
```

Converts only Email addresses:

```php
$string = link\auto\Link($string, 'email');
```

The third parameter determines whether links are shown in a new window. The value can be TRUE or FALSE (boolean):

```php
$string = link\autoLink($string, 'both', TRUE);
```