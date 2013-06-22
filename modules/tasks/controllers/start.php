<?php
defined('STDIN') or exit('Access Denied');

Class Start extends Controller {
    
    function __construct()
    {   
        parent::__construct();
    }         
    
    public function index()
    {   
       echo "\033[36m".'
        _____      ________     __     __  __        __          _______
      / ___  /    / ____   \   / /    / / / /       / /         / ___   /
    /  /   /  /  / /____/  /  / /    / / / /       / /        /  /   /  /
   /  /   /  /  / _____  /   / /    / / / /       / /        /  /   /  /
  /  /___/  /  / /____/  \  / /____/ / / /____   / /_____   /  /__ /  /
  /_______/   /__________/ /________/ /_______/ /_______ /  /_______/ 
  
                Welcome to Obullo Task Manager (c) 2013
 Please run '."\033[31m".'[$php task.php start help] '."\033[0m"."\033[31m".'You are in /MODULES/TASKS FOLDER '."\033[0m";
    }
    
    public function help()
    {
    echo "\nOBULLO GENERAL HELP FOR TASK OPERATIONS\n";
        
        echo "
    MANAGING TASKS IN /MODULES/TASKS FOLDER
    1 . Obullo has a '/tasks' folder in /modules directory.
    2 . In tasks folder you can create /model, /helpers, /views folders like other modules.
    3 . Also you can call hmvc requests to other modules.
    4 . Manually you can run a task controller like this : \n\t > \$php task.php controller method argument1 argument2 ...\n
    5 . Also you can run cmd commands using task helper: \n\t > loader::helper('ob/task'); task_run('module/controller/method arg1 arg2 arg3'); ...\n

    MANAGING TASKS IN CURRENT MODULE
    1 . You can also create '/tasks' folder in a module like this /modules/welcome/tasks/.
    2 . If you prefer this way you should just put your task controllers to in it, then Obullo tasks\noperations will work from this folder.
    3 . You don't need to create /model, /helpers, /views folders again, you already had it in current\nmodule.
    4 . Manually you can run a task controller like this : \n\t > \$php task.php module controller method argument1 argument2 ...\n
    look at / User Guide / General Topics / Tasks / for more details.\n\n";
    }
    
}

/* End of file start.php */
/* Location: .modules/tasks/start.php */