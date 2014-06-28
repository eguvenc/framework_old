<?php

defined('STDIN') or die('Access Denied');

/**
 * $app help
 * 
 * @var Controller
 */
$app = new Controller;

$app->func(
    'index',
    function () {

        echo "\33[1;36m".'
        ______  _            _  _
       |  __  || |__  _   _ | || | ____
       | |  | ||  _ || | | || || ||  _ |
       | |__| || |_||| |_| || || || |_||
       |______||____||_____||_||_||____|

        Welcome to Task Manager (c) 2014
You are running $php task help command which is located in app / tasks folder.'."\n\033[0m\n";

echo "\33[1;36mAvailable commands:\33[0m\33[0;36m
log        : Follow the application log file.
clear      : Clear application log data.
update     : Update your Obullo version.
host       : Create a virtual host file for apache2.
help       : See list all of available commands.\33[0m\n\n";

echo "\33[1;36mUsage:\33[0m\33[0;36m
php task [command] [arguments]\n\33[0m\n";

    }
);

/* End of file help.php */
/* Location: .app/tasks/controller/help.php */