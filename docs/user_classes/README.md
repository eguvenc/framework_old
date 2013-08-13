## User Classes

```php
namespace Test {

    Class start {

        function __construct()
        {
            \Ob\log\me('Test Helper Initialized !');
        }       
    }
    
    function me()
    {
       echo 'Hello me !';
    }
    
    Class Test
    {
        function me()
        {
            echo 'Hello !';
        }
    }
}

// Calling your test functions. ( helpers )
new \test\start();

\test\me();  // output Hello me !

// Calling your Test Class.

$test = new \Test\Test();
$test->me(); // outpput Hello !;

```

## Include User Classes

```php
namespace Test {

    require CLASSES .'test/src/otherclass.php';
    
    Class Test
    {
        function __construct()
        {
            \Ob\log\me('Test Class Initialized !');
        }
    }

    // ...
};

```
