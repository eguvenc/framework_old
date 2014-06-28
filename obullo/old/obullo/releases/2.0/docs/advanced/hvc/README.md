## HVC <a name="hvc"></a>

### What is the HVC ?

------

#### Hierarchical-View-Controller (HVC)

The HVC pattern decomposes the client tier into a hierarchy of parent-child VC layers. The repetitive application of this pattern allows for a structured client-tier architecture.

```php
VC    
          _____________
         |             |     
         | Controller  |
         |_____________|
                       \
                        \ ______________
                         |              |
                         |    View      |        
                         |______________|
```

```php
HVC ( Layered VC )

        ------  
        | c  | -------
        ------        \
             \         \
              -------   \
              |  v  |    \
              -------   ------
                        | c  | ------   
                        ------        \
                              \        \
                              -------   \
                              |  v  |    \
                              -------   ------
                                        | c  |          
                                        ------
                                              \
                                              -------
                                              |  v  |
                                              -------    
```

HVC pattern offers more flexibility in your application. You can call multiple requests using Hvc Class.

```php
------------------------------------------------------------------------------------
|                                                                                   |
|                   echo $this->hvc->get('views/header');                           |
|                                                                                   |
|                                                                                   |
|-----------------------------------------------------------------------------------|
|                                                                                   |
|                                                                                   |
|                                                                                   |
|                                                                                   |
|                                                                                   |
|                                                                                   |
|                                                                                   |
|                                                                                   |
|                                                                                   |
|                                                                                   |
|                                                                                   |
-------------------------------------------------------------------------------------
|                                                                                   |
|                   echo $this->hvc->get('views/footer');                           |
|                                                                                   |
-------------------------------------------------------------------------------------
```

Above the example call the **views controllers** from <b>/public/views/controller</b> folder and fetches output of them.