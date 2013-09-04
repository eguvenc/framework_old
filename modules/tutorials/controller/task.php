<?php

Class Task extends Controller {    
                                      
    function __construct()
    {        
        parent::__construct();
    }         

    function index($mode = '')
    {   
        if(PHP_OS != 'Linux')
        {
            die('Please run task functionality under the linux platforms.');
        }
        
        new task\start();

        echo url\anchor('tutorials/task/index/help', 'Click Here to Help.');

        if($mode == 'help')
        {
            $output = task\run('start/help', true);  // use without true when you go live.
            echo '<pre>'.$output.'</pre>';
        }
        else
        {
            $output = task\run('start/index', true); // use without true when you go live.
            echo '<pre>'.$output.'</pre>';
        }
    }
}

/* End of file task.php */
/* Location: .modules/tutorials/controller/task.php */