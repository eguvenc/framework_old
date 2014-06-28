## Task Class

The task class helps you use CLI operations ( running shell scripts etc..) inside the php interface.


### Initializing a Task Class

------

```php
new Task;
$this->task->method();
```

#### Running Tasks

The task uri works like framework uri it calls the <kbd>controller/method/arguments</kbd>

```php
$this->task->run('start/index/arg1/arg2');
```

Look at the Tasks and CLI Requests at <kbd>(/docs/advanced/tasks-and-cli-requests)</kbd> section for more details.

### Function Reference

------

#### $this->task->run('uri', $debug = false);

Using $this->task->run() function run your tasks as a using shell command in the background.