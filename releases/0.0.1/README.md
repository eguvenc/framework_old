## Task Helper

The task helper helps to you use CLI operations (cron jobs, running shell scripts etc..) inside the php interface.

### Loading task helper

-------

This helper is loaded using the following code:

```php
task\start();
```

#### Running Tasks

The first segment call the <kbd>module</kbd> and others calls <kbd>controller/method/arguments</kbd>

```php
task\run('welcome hello/index/arg1/arg2');
```

Look at the Tasks and CLI Requests at <kbd>(/docs/advanced/tasks-and-cli-requests)</kbd> section for more details.

### Function Reference

------

#### task\run('uri', $debug = false);

Using task\run() function you can run your tasks as a command in the background.