<?php
defined('STDIN') or die('Access Denied');

Class Start extends Controller {
    
    function __construct()
    {   
        parent::__construct();
    }         
    
    public function index()
    {   
        echo "\33[0;36m".'
        ______  _            _  _
       |  __  || |__  _   _ | || | ____
       | |  | ||  _ || | | || || ||  _ |
       | |__| || |_||| |_| || || || |_||
       |______||____||_____||_||_||____|

        Welcome to Task Manager (c) 2013
Please run [$php task start help] You are in [/MODULES/TASKS FOLDER]'."\n\033[0m";
    }
    
    public function help()
    {
    echo "\nOBULLO GENERAL HELP FOR TASK OPERATIONS\n";
        
        echo "
    MANAGING TASKS IN /MODULES/TASKS FOLDER
    1 . Obullo has a '/tasks' folder in /modules directory.
    2 . Manually you can run a task controller like this : \n\t > \$php task controller method argument1 argument2 ...\n
    3 . You can run php files from command line using task helper: \n\t > new task\start; task/run('module/controller/method arg1 arg2 arg3'); ...\n

    MANAGING TASKS IN CURRENT MODULE
    1 . You can also create '/tasks' folder in a module like this /modules/welcome/tasks/.
    2 . If you prefer this way you should just put your task controllers to in it, then Obullo tasks\noperations will work from this folder.
    4 . Manually you can run a task controller like this : \n\t > \$php task module controller method argument1 argument2 ...\n
    look at / User Guide / General Topics / Tasks / for more details.\n\n";
    }
    
}

/* End of file start.php */
/* Location: .modules/tasks/start.php */