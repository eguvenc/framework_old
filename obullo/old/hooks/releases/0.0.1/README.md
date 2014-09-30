
## Hooks - Extending To Core

Hooks feature provides a means to tap into and modify the inner workings of the framework without hacking the core files. When framework runs it follows a specific execution process. There may be instances, however, where you'd like to cause some action to take place at a particular stage in the execution process. For example, you might want to run a script right before your controllers get loaded, or right after, or you might want to trigger one of your own scripts in some other location.


### Enabling Hooks

The hooks feature can be globally <b>enabled/disabled</b> by setting the following item in the <kbd>app/config/config.php</kbd> file:

```php
$config['enable_hooks'] = true;
```

### Defining a Hook

Hooks are defined in <kbd>app/config/hooks.php</kbd> file. Each hook is specified as an array with this prototype:

```php
$hooks['pre_controller'] = function(){
        // your codes ...
};
```
```php
$hooks['pre_controller'] = function(){
        $class = new MyClass();
        $class->method();
};
```

**Notes:**
The <b>$hooks</b> array index correlates to the name of the particular hook point you want to use. In the above example the hook point is pre_controller. A list of hook points is found below. The following items should be defined in your associative hook array:

### Hook Points

The following is a list of available hook points.

* <b>pre_system</b>
Called very early during system execution. Only the benchmark and hooks class have been loaded at this point. No routing or other processes have happened.
* <b>pre_controller</b>
Called immediately prior to any of your controllers being called. All base classes, routing, and security checks have been done.
* <b>post_controller_constructor</b>
Called immediately after your controller is instantiated, but prior to any method calls happening.
* <b>post_controller</b>
Called immediately after your controller is fully executed.
* <b>display_override</b>
Overrides the _display() function, used to send the finalized page to the web browser at the end of system execution. This permits you to use your own display methodology. Note that you will need to get the Controller Instance with getInstance() and then the finalized data will be available by calling getInstance()->output->getOutput();
* <b>post_system</b>
Called after the final rendered page is sent to the browser, at the end of system execution after the finalized data is sent to the browser.
