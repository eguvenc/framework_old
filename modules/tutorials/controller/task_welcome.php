<?php

Class Task_Welcome extends Controller {    
                                      
    function __construct()
    {        
        parent::__construct();
    }         

    function index($mode = '')
    {
        echo Url::anchor('tutorials/task_welcome/index/help', 'Click Here to Help.');

        if($mode == 'help')
        {
            $output = Task::run('start/help', true);  // use without true when you go live.
            echo '<pre>'.$output.'</pre>';
        }
        else
        {
            $output = Task::run('start/index', true); // use without true when you go live.
            echo '<pre>'.$output.'</pre>';
        }
    }
}

/* End of file task_welcome.php */
/* Location: .modules/tutorials/controller/task_welcome.php */