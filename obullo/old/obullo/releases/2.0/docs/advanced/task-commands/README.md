
## Task Commands<a name="task-commands"></a>

The task package helps to you use CLI operations ( running shell scripts, unix commands ).

Useful task commands.

**Note:** These commands requires <kbd>Task Package</kbd>.

#### Log ( Console Debug )

```php
root@localhost:/var/www/framework$ php task log
```

```php
DEBUG - 2013-09-13 06:39:44 --> Application Autoload Initialized 
DEBUG - 2013-09-13 06:39:44 --> Html Helper Initialized 
DEBUG - 2013-09-13 06:39:44 --> Url Helper Initialized 
DEBUG - 2013-09-13 06:39:44 --> Application Autorun Initialized 
DEBUG - 2013-09-13 06:39:44 --> View Class Initialized 
DEBUG - 2013-09-13 06:39:44 --> View file loaded: PUBLIC_DIR/views/footer.php 
DEBUG - 2013-09-13 06:39:44 --> View file loaded: PUBLIC_DIR/views/welcome.php 
DEBUG - 2013-09-13 06:39:44 --> Final output sent to browser 
BENCH - 2013-09-13 06:39:44 --> Loading Time Base Classes: 0.0013 
BENCH - 2013-09-13 06:39:44 --> Execution Time ( Welcome / Welcome / Index ): 0.0021 
BENCH - 2013-09-13 06:39:44 --> Total Execution Time: 0.0034 
BENCH - 2013-09-13 06:39:44 --> Memory Usage: 700,752 bytes 
________LOADED FILES______________________________________________________

Helpers   --> ROOT/packages/html/html.php, ROOT/packages/url/url.php
__________________________________________________________________________

        ______  _            _  _
       |  __  || |__  _   _ | || | ____
       | |  | ||  _ || | | || || ||  _ |
       | |__| || |_||| |_| || || || |_||
       |______||____||_____||_||_||____|

        Welcome to Log Manager (c) 2013
Display logs [$php task log], to filter logs [$php task log $level]
```


#### Clear ( Clear Log & Cache files )

When you move your project to another server you need to clear log files and caches. Go to your terminal and type your project path the run the clear.

```php
root@localhost:/var/www/framework$ php task clear 
/* All log files deleted. */
```

#### Running Your Tasks

The first segment calls the <kbd>module</kbd> and others call <kbd>controller/method/arguments</kbd>

```php
$this->task->run('welcome/hello/index/arg1/arg2');
```

Using $this->task->run() function you can run your tasks.


### CLI (Command Line Interface)

------

First of all go your framework root folder.

```php
$cd /var/www/framework/
```

All command line requests go to framework <b>task</b> file which is located in your root.


```php
$php task start
```

Above the command calls the <samp>start</samp> controller from <b>tasks</b> folder which is located in your <kbd>modules/tasks</kbd>.

```php
        ______  _            _  _
       |  __  || |__  _   _ | || | ____
       | |  | ||  _ || | | || || ||  _ |
       | |__| || |_||| |_| || || || |_||
       |______||____||_____||_||_||____|

        Welcome to Task Manager (c) 2014
Please run [$php task start help] You are in [ modules / tasks ] folder.
```

If you see this screen your command successfully run <b>otherwise</b> check your <b>php path</b> running by this command

```php
$which php // command output /usr/bin/php 
```

If your current php path is not <b>/usr/bin/php</b> open the <b>constants</b> file and define your php path. 

```php
define('PHP_PATH', 'your_php_path_that_you_learned_by_which_command'); 
```