## Navbar Class

-------

Navbar Class provides a simple navigation bar management for <b>ul</b> based menus.

### Initializing the Class

-------

```php
new Navbar();
$this->navbar->method();
```

Once loaded, the Navbar object will be available using: <kbd>$this->navbar->method();</kbd>

### Grabbing the Instance

------

Also using new Navbar(false); boolean you can grab the instance of Obullo libraries,"$this->navbar->method()" will not available in the controller.

```php
$acl = new Navbar(false);
$acl->method();
```

### Configuration

------

Go to your <kbd>app/config/navbar.php</kbd> file and set your navigation ( ul tag ) items.

```php
/*
|--------------------------------------------------------------------------
| Top Levels
|--------------------------------------------------------------------------
|
| $navbar['toplevel'][]['users']    = array('label' => 'Members', 'url' => 'users/list_all');
| $navbar['toplevel'][]['articles'] = array('label' => 'Articles', 'url' => 'articles/list_all');
|
*/
$navbar['toplevel'][]['users'] = array('label' => 'Members', 'url' => 'users/list_all');

|--------------------------------------------------------------------------
| Sub Levels
|--------------------------------------------------------------------------
|  
| $navbar['sublevel']['users'][]    = array('key' => 'save', 'label' => 'Add Member', 'url' => 'users/save');
| $navbar['sublevel']['articles'][] = array('key' => 'save', 'label' => 'Add Article','url' => 'articles/save');
*/
$navbar['sublevel']['users'][] = array('key' => 'save', 'label' => 'Add Member', 'url' => 'users/save');
```

Use your autoload file which is located <kbd>app/config/autoload.php</kbd>

```php
$autoload['library']    = array('navbar');
```

### Customizing Css

------

```php
#navigation-top-level  // Top navigation bar div. 
#navigation-sub-level {}   // Sub navigation bar div. 

.navbar-top-active { }   // Active top navigation li item. 
.navbar-top-inactive { }   // Inactive top navigation li item. 
.navbar-sub-active { }  // Active sub navigation li item. 
.navbar-sub-inactive { }  // Inactive sub navigation li item. 
```

### Rendering Navigation Bar Items

```php
echo '<div id="navigation-top-level">';

$total = $this->navbar->count('toplevel');
if ($total > 0)
{
    echo '<ul>';
    foreach ($this->navbar->getTopLevel() as $i => $toplevel)
    {
        $lastItem = ($i == $total) ? ' class="last" ' : '';    
        echo '<li '.$lastItem.'>'.$toplevel.'</li>'; 
    }
    echo '</ul>';
}

echo '</div>';

echo '<div id="navigation-sub-level">';

if ($this->navbar->count('sublevel') > 0)
{
    echo '<ul>';
    foreach ($this->navbar->getSubLevel() as $sublevel)
    {
        echo '<li>'.$sublevel.'</li>';
    }
    echo '</ul>';
}

echo '</div>';
```

### Function Reference

------

#### $this->navbar->getTopLevel()

Get top level navigation items using the first module segment(0).

#### $this->navbar->getSubLevel()

Get sub level navigation items using the first uri segment(0) and second uri segment(1).

#### $this->navbar->count($key = 'toplevel')

Returns the number of items by provided navigation level.