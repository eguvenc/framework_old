<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         obullo
 * @author          obullo.com
 * @license         public
 * @since           Version 1.0
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Obullo Task Helpers
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @link
 */

/**
* Run Command Line Tasks
*
* @author CJ Lazell
* @param  array $uri
* @return void
*/
if ( ! function_exists('task_run'))
{
    function task_run($uri, $debug = FALSE)
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

        if($debug)
        {
            // @todo escapeshellcmd();
            $output = shell_exec($shell);

            // clear console colors
            // $output = trim(preg_replace('/\n/', '#', $output), "\n");
            $output = preg_replace(array('/\033\[36m/','/\033\[31m/','/\033\[0m/'), array('','',''), $output);
            log_me('debug', 'Task function output -> '. $output);
            
            return $output;
        }
        else   // continious task
        {
            // @todo escapeshellcmd();
            
            shell_exec($shell.' > /dev/null &');
        }

        log_me('debug', 'Task function command -> '. $shell);
    }
}

/* End of file task.php */
/* Location: ./obullo/helpers/task.php */
