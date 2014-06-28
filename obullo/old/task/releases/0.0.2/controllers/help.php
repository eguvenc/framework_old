<?php
defined('STDIN') or die('Access Denied');

/**
 * $c help
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
});

$c->func('index', function(){

    echo "\nGENERAL HELP FOR TASK OPERATIONS\n";
        
    echo "
YOU ARE IN APP/TASKS FOLDER\n
    1 . Running a task controller : \n\t > \$php task controller method argument1 argument2 ...\n
    2 . Running php files using task package: \n\t\n\tnew Task; \n\t\n\t\$this->task->run('controller/method/arg1/arg1 ..'); \n\t \n\t\n";
});

/* End of file help.php */
/* Location: .app/tasks/controller/help.php */