## Task Helper

Obullo has a task helper file to using CLI operations (cron jobs, running shell scripts etc..) inside the php interface.

### Function Reference

------

Using task\run() function you can run your tasks as a command in the background. Function run like CLI but don't use the task.php.

#### task\run('uri', $debug = FALSE);

### Loading task helper

-------

This helper is loaded using the following code:

```php
task\run();
```

#### Running Tasks

The first segment call the <samp>module</samp> and others calls <samp>Controller/method/arguments</samp>

```php
task\run('welcome hello/index/arg1/arg2');
```

Look at the [Tasks and CLI Requests](/docs/advanced/#tasks-and-cli-requests) section for more details.