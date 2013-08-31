## XML Helper

The XML Helper file contains functions that assist in working with XML data.

### Loading this Helper

------

This helper is loaded using the following code:

```php
new xml\start();
```

The following functions are available:

#### xmlConvert('string')

Takes a string as input and converts the following reserved XML characters to entities:

Ampersands: &
Less then and greater than characters: < >
Single and double quotes: '  "
Dashes: -

This function ignores ampersands if they are part of existing character entities. Example:

```php
$string = xmlConvert($string);
```

#### xmlWriter('array data', $cdata = FALSE, $encoding = 'UTF-8')

If PECL libxml extension loaded on your host, xml writer function convert your array data to xml.

```php
new xml\start();
        
 $array = array('item1' => 'thing',
                       'item2' => array('door'   => 'red',
                                        'window' => 'clear'
                                       ),
                       'item3' => 'thing'
                      );    

 echo xmlWriter($array);

 // Output
<?xml version="1.0" encoding="UTF-8"?>
<root><item1>thing</item1>
<item2><door>red</door><window>clear</window></item2>
<item3>thing</item3>
</root>
```

If you need to use **html data** in your xml tags, set second param as TRUE.

```php
xmlWriter($array, TRUE);
```