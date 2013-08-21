## Navbar Class

Navbar Class provides a simple navigation bar management for <b>ul</b> based menus.

### Configuration

------

Go to your <dfn>app/config/navbar.php</dfn> file and set your navigation ( ul tag ) items.

```php
/*
|--------------------------------------------------------------------------
| Top Levels
|--------------------------------------------------------------------------
|
| $navbar['top_level'][]['users']    = array('label' => 'Members', 'url' => 'backend/users/list_all');
| $navbar['top_level'][]['articles'] = array('label' => 'Articles', 'url' => 'backend/articles/list_all');
|
*/
$navbar['top_level'][]['users'] = array('label' => 'Members', 'url' => 'backend/users/list_all');

|--------------------------------------------------------------------------
| Sub Levels
|--------------------------------------------------------------------------
|  
| $navbar['sub_level']['users'][]    = array('key' => 'save', 'label' => 'Add Member', 'url' => 'backend/users/save');
| $navbar['sub_level']['articles'][] = array('key' => 'save', 'label' => 'Add Article','url' => 'backend/articles/save');
*/
$navbar['sub_level']['users'][] = array('key' => 'save', 'label' => 'Add Member', 'url' => 'backend/users/save');
```

### Initializing the Class

------

Use your autoload file which is located <dfn>app/config/autoload.php</dfn>

```php
$autoload['helper']['ob/view'] = '';
$autoload['helper']['ob/html'] = '';
$autoload['helper']['ob/url']  = '';
$autoload['helper']['ob/form'] = '';
$autoload['helper']['ob/session'] = '';

$autoload['lib']['ob/auth']   = array();
$autoload['lib']['ob/navbar'] = array();
$autoload['config']     = array();
$autoload['lang']       = array();
$autoload['model']      = array();
```

If use navbar class for backend module you need to set backend autoload file which is located <dfn>sub.backend/config/autoload.php</dfn>

Once loaded, the navbar object will be available using: <dfn>$this->navbar->method();</dfn>

```php
$autoload['helper']['ob/view'] = '';
$autoload['helper']['ob/html'] = '';
$autoload['helper']['ob/url']  = '';
$autoload['helper']['ob/form'] = '';
$autoload['helper']['ob/session'] = '';

$autoload['lib']['ob/auth']   = array(array('module' => 'sub.backend'));
$autoload['lib']['ob/navbar'] = array(array('module' => 'sub.backend'));
$autoload['config']     = array();
$autoload['lang']       = array();
$autoload['model']      = array();
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

$total = $this->navbar->count('top_level');
if ($total > 0)
{
    echo '<ul>';
    foreach ($this->navbar->top_level() as $i => $top_level)
    {
        $last_item = ($i == $total) ? ' class="last" ' : '';    
        echo '<li '.$last_item.'>'.$top_level.'</li>'; 
    }
    echo '</ul>';
}

echo '</div>';

echo '<div id="navigation-sub-level">';

if ($this->navbar->count('sub_level') > 0)
{
    echo '<ul>';
    foreach ($this->navbar->sub_level() as $sub_level)
    {
        echo '<li>'.$sub_level.'</li>';
    }
    echo '</ul>';
}

echo '</div>';
```

### Function Reference

------

#### $this->navbar->top_level()

Get top level navigation items using the first module segment(0).

#### $this->navbar->sub_level()

Get sub level navigation items using the first uri segment(0) and second uri segment(1).


#### $this->navbar->count($key = 'top_level')

Returns the number of items by provided navigation level.