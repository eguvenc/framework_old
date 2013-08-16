<?php
namespace task {

    /**
     * Task Helper
     *
     * @package     Task
     * @subpackage  0.0.1
     * @version     0.0.1
     * @link
     */

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

            log\me('debug', 'Task function output -> '. $output);

            return $output;
        }
        else   // continious task
        {
            // @todo escapeshellcmd();

            shell_exec($shell.' > /dev/null &');
        }

        log\me('debug', 'Task function command -> '. $shell);
    }

}

/* End of file task.php */
/* Location: ./ob/task/releases/0.0.1/task.php */
