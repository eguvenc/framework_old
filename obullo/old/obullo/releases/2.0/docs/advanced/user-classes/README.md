## User Classes

The <kbd>app/classes</kbd> folder is reserved for user classes. Below the examples demonstrate creating your class files.

### Creating Your Classes

------

```php
<?php
Class Doly {

    public function __construct()
    {
        if( ! isset(getInstance()->doly) )
        {
            getInstance()->doly = $this;
        }

        $this->logger->debug('My Doly Class Initialized');
    }

    public function hello()
    {
        echo 'Hello World !';
    }

}

new Doly;
// $this->doly->hello();   // output Hello World !
?>
```

### Including Sources

Simply include sources into your classes.

```php
require CLASSES .'test/src/otherclass.php';

Class Test
{
    function __construct()
    {
        if( ! isset(getInstance()->test) )
        {
            getInstance()->test = $this;
        }
        
        $this->logger->debug('Test Class Initialized');
    }

    function me()
    {
        echo 'Hello !';
    }
}
```