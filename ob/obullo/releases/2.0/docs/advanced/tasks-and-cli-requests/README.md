##Tasks and CLI Requests <a name="tasks-and-cli-requests"></a> 

Obullo has a integrated task functionality and Command Line Interface support who want to create command line tasks. You can run <b>controllers</b> from command line.

### CLI (Command Line Interface) Requests

------

To run task controller which is located in app/task folder just type below the codes in your Terminal. Below the example we use <samp>/var/www</samp> path this is your webroot and it depends on your configuration.

First of all go your framework root folder.

```php
$cd /var/www/framework/
```

Do a request, all command line requests goes to Obullo <b>task.php</b> which is located in your root directory.

```php
$php task.php hello
```

This request call the <samp>hello</samp> controller from <b>tasks</b> folder which is located in your /<samp>app/tasks</samp> directory.

```php
        _____      ________     __     __  __        __          _______
      / ___  /    / ____   \   / /    / / / /       / /         / ___   /
    /  /   /  /  / /____/  /  / /    / / / /       / /        /  /   /  /
   /  /   /  /  / _____  /   / /    / / / /       / /        /  /   /  /
  /  /___/  /  / /____/  \  / /____/ / / /____   / /_____   /  /__ /  /
  /_______/   /__________/ /________/ /_______/ /_______ /  /_______/ 
  
                Welcome to Obullo Task Manager (c) 2011.
     Please run this command [$php task.php hello help] for help ! 
                YOU ARE IN /APP/TASKS FOLDER
```

If you see this screen your command successfully run <b>otherwise</b> check your <b>php path</b> running by this command

```php
$which php // command output /usr/bin/php 
```

If your current php path not <b>/usr/bin/php</b> open the index.php which is located in your framework root and define your php path like this 

```php
define('PHP_PATH', 'your_php_path_that_you_learned_by_which_command'); 
```

### Running Module Tasks

------

Running below the code will call the another task controller from <dfn>/MODULES/tasks</dfn> directory.

```php
$php task.php start
```

Running below the code will call the another task controller from your <dfn>/MODULES/welcome/tasks</dfn> directory.

```php
$php task.php welcome start index arg1 arg2
```

If above the command successful you will see this screen

```php
Module: welcome
Hello World !
Argument 1: arg1
Argument 2: arg2
The Start Controller Index function successfully works !
```

**Note:** When you use the CLI operations *cookies* and *sessions* will not works as normal. Please use the tasks for advanced Command Line structures.

### Tasks

------

Tasks are same thing like controller model view structure. The <b>main difference from CLI</b> in the <b>task mode</b> framework rules works like browsing a web page <b>sessions</b> and another some things works well in this mode.To working with tasks just we call the controller from the command line and we have special directories for this operation called tasks.

Obullo has <b>three</b> tasks folder called APPLICATON, MODULES and YOUR MODULE TASKS folder.

#### APPLICATION TASK FOLDER

```php
- app
    + config
    + core
    + helpers
    + libraries
    + models
    + parents
    - tasks
       - controllers
           hello.php
```

#### MODULES TASK FOLDER

```php
+ app
- modules
   + captcha
   + default
   - tasks
       - controllers
            start.php
```

#### YOUR MODULE TASK FOLDER

```php
+ app
- modules
   + captcha
   + default
   - welcome
      - tasks
        - start
           start.php
```

### Running APPLICATION Tasks

------

All command line request goes to Obullo <b>task.php</b> which is located in your root directory. Obullo has a <b>/tasks</b> folder in the application directory if you want to create application tasks just create your controllers, models, helpers .. files in this folder.

Using [Task Helper](/docs/packages/#task-helper) you can run your tasks as a command in the background. Function run like CLI but don't use the task.php.

```php
new task\start();

task\run('hello help', $debug = TRUE);
```

Running MODULE Tasks

------

```php
new task\start();

task\run('start help', $debug = TRUE);
```

### Running YOUR MODULE Tasks

------

```php
new task\start();

task\run('welcome start/index/arg1/arg2', $debug = TRUE);
```

<b>An Important thing</b> we use the second argument <b>$debug = true</b> just for test don't use this argument except the testing or use it as false.

```php
task\run('welcome hello/index/arg1/arg2', FALSE);
```

**Note:** When you use the task function, debug mode should be <samp>FALSE</samp>, or don't use a second argument except the test otherwise shell command will print output the screen.