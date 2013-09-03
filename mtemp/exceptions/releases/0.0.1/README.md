### Exceptions

You can use try catch blocks in your application.

```php
try
{
    throw new Exception('blabla');
    
} catch(Exception $e)
{
    echo $e->getMessage();  //output blabla 
}
```