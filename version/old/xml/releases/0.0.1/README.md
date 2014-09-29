## XML Helper

The XML Helper file contains functions that assist in working with XML data.


The following functions are available:

#### $this->xml->convert('string')

Takes a string as input and converts the following reserved XML characters to entities:

* Ampersands: <b>&</b>
* Less then and greater than characters: <b>< ></b>
* Single and double quotes: <b>'  "</b>
* Dashes: <b>-</b>

This function ignores ampersands if they are part of existing character entities. Example:

```php
<?php
echo $this->xml->convert("hello - this is 'John & Mary' our emails <johnandmary@example.com> Blabla");

// hello - this is 'John &amp; Mary' our emails &lt;johnandmary@example.com&gt;
```

#### $this->xml->writer('array data', $cdata = false, $encoding = 'UTF-8', $version = '1.0')

If PECL libxml extension loaded on your host, xml writer function convert your array data to xml.

```php
<?php
$array = array('item1' => 'thing',
                      'item2' => array('door'   => 'red',
                                       'window' => 'clear'
                                      ),
                      'item3' => 'thing'
                     );    

 echo $this->xml->writer($array);

// Output
/*
<?xml version="1.0" encoding="UTF-8"?>
<root><item1>thing</item1>
<item2><door>red</door><window>clear</window></item2>
<item3>thing</item3>
</root>
*/
```

If you need to use **html data** in your xml tags, set second param as true.

```php
<?php
$this->xml->writer($array, true);
```