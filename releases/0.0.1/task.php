<?php
namespace task {
    
    /**
    * Task ( Cli ) Helper
    *
    * @package       packages
    * @subpackage    task
    * @category      cli
    * @link
    */

    Class start
    {
        function __construct()
        {
            \logMe('debug', 'Task Helper Initialized');
        }
    }

    // ------------------------------------------------------------------------

    /**
    * Run Command Line Tasks
    *
    * @param  array $uri
    * @return void
    */
    function run($uri, $debug = false)
    {
        $uri    = explode('/', trim($uri));
        $module = array_shift($uri);

        foreach($uri as $i => $section)
        {
            if( ! $section)
            {
                $uri[$i] = 'false';
            }
        }

        $shell  = PHP_PATH .' '. FPATH .'/'. TASK_FILE .' '. $module .' '. implode('/', $uri) .' OB_TASK_REQUEST';

        if($debug) // Enable debug output to log folder.
        {
            // @todo escapeshellcmd();

            // clear console colors
            // $output = trim(preg_replace('/\n/', '#', $output), "\n");
            $output = preg_replace(array('/\033\[36m/','/\033\[31m/','/\033\[0m/'), array('','',''), shell_exec($shell));

            \logMe('debug', 'Task function output -> '. $output);

            return $output;
        }
        else   // continious task
        {
            // @todo escapeshellcmd();

            shell_exec($shell.' > /dev/null &');
        }

        \logMe('debug', 'Task function command -> '. $shell);
    }

}

/* End of file task.php */
/* Location: ./packages/task/releases/0.0.1/task.php */
