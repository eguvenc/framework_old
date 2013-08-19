## Uri Class

The Uri Class file contains functions that assist in working with URLs.

### Initializing the Class

------

```php
new Uri();
```

Once loaded, the Uri library object will be available using: <dfn>$this->uri->method();</dfn>

### Grabbing the Instance

------

Also using new Uri(false); boolean you can grab the instance of Obullo libraries,"$this->uri->method()" will not available in the controller.

```php
$uri = new Uri(false);
$uri->method();
```

#### extension()

You can use uri extensions when you use ajax, xml, rss, json.. requests, you can dynamically change the application behaviours using uri extensions. Also this functionality will help you to create friendly urls.

example.com/module/class/post.json
You can define allowed extensions from your application/config/config.php file, default allowed URI extensions listed below.
php
html
json
xml
raw
rss
ajax
Using URI Class $this->uri->extension(); function you can grab the called URI extension.
