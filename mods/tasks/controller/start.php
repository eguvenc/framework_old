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
Please run [$php task start help] You are in [ mods/ tasks ] folder.'."\n\033[0m";
    }
    
    public function help()
    {
    echo "\nOBULLO GENERAL HELP FOR TASK OPERATIONS\n";
        
    echo "
YOU HAVE A '/MODS/TASKS' FOLDER\n
    1 . Running a task controller : \n\t > \$php task controller method argument1 argument2 ...\n
    2 . Running php files using task helper: \n\t\n\t[php] \n\t\tnew task\start(); \n\t\ttask\\run('module/controller/method/arg1/arg1 ..'); \n\t[/php] \n\t\n";
    }
    
}

/* End of file start.php */
/* Location: .mods/tasks/controller/start.php */