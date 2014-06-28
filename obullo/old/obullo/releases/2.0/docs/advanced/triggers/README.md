## Auto-running <a name="auto-running"></a>

### Autorun Functions

Framework has an <b>autorun.php</b> that is located in your <kbd>app/config</kbd> folder.

This file specifies which functions should be run by default in <b>__construct() level</b> of the controller.
In order to keep the framework as light-weight as possible only the absolute minimal resources are run by default.This file lets you globally define which systems you would like run with every request.

```php
$autorun[] = function() {
	$this->translator->load('spanish');
};
```

You can use <b>$this</b> variable in your function. 

```php
$autorun[] = function() {
	
	new Cookie;

	if($this->cookie->get('langName') == 'spanish')
	{
		$this->translator->load('spanish');
	}
};
```

**Note:** If you need a bootstrap level autorun functionality look for the <kbd>docs/advanced/hooks</kbd> section.