## HMVC <a name="hmvc"></a>

### What is the HMVC ?

------

#### Hierarchical-Model-View-Controller (HMVC)

The HMVC pattern decomposes the client tier into a hierarchy of parent-child MVC layers. The repetitive application of this pattern allows for a structured client-tier architecture. Obullo has a simple HMVC library and Obullo's simple HMVC library support <b>internal requests</b> at this time.

```php
MVC    
                  _____________
                 |             |     
                 | Controller  |
                 |_____________|
                /              \
 _____________ /                \ ______________
|             |                  |              |
|   Model     | ---------------- |    View      |        
|_____________|                  |______________|
```

```php
HMVC (Layered MVC)

        ------  
        | c  | -------
        ------        \
       /      \        \
------        -------   \
| m  |        |  v  |    \
------        -------   ------
                        | c  | ------   
                        ------        \
                       /      \        \
                ------        -------   \
                | m  |        |  v  |    \
                ------        -------   ------
                                        | c  |          
                                        ------
                                       /      \
                                ------        -------
                                | m  |        |  v  |
                                ------        -------    
```

HMVC pattern offers more flexibility in your application. You can call multiple requests using Obullo's Request helper.

```php
------------------------------------------------------------------------------------
|                                                                                   |
|          echo request('home/start/header')->exec();                               |
|                                                                                   |
|                                                                                   |                                   
|-----------------------------------------------------------------------------------|
|                                                                                   |
|                                                                                   |
| echo request('news/start/sports/tennis')->exec();                                 |
|                                                                                   |
|                                                                                   |                                   
|                                                                                   |
|                                                                                   |
| echo request('news/start/economy/exchange')->exec();                              |
|                                                                                   |
|                                                                                   |
|                                                                                   |
-------------------------------------------------------------------------------------
|                                                                                   |
|  $footer = request('home/start/footer', $params =array() , $cache = 1000);        |
|                                                                                   |
|             echo $footer->exec();                                                 |     
|                                                                                   |
-------------------------------------------------------------------------------------
```

Another example, we created a <b>captcha</b> module using hmvc methods download the latest version of Obullo and look at the captcha module. And just an idea you can create a login service creating a <b>login</b> folder then you can do request from your controllers using HMVC.