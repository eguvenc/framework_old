<?php

defined('STDIN') or die('Access Denied');

error_reporting(E_ALL | E_NOTICE | E_STRICT); // error reporting

set_error_handler(
    function ($errno, $errstr, $errfile, $errline) {
        echo("\n\033[1;31mError: $errstr . ' - error code: '.$errno.' errorfile : - '.$errfile.' - errorline: '.$errline \033[0m"); // Do something other than output message.   
    }
);

/**
 * $app log
 * 
 * @var Controller
 */
$app = new Controller;

$app->func(
    'index',
    function ($level = '') {

        if ( ! empty($level) AND ! in_array($level, array('emergency','alert','critical','error','warning','notice','info','debug'))) {
        
            $this->_displayLogo();
            echo "\33[1;31m\nThe log level: '".$level."' not supported.\33[0m\n\n";
            echo "\33[1;36mAvailable log severities:\33[0m\n\33[0;36m
emergency  : Emergency: system is unusable.
alert      : Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.
critical   : Critical conditions. Example: Application component unavailable, unexpected exception.
error      : Runtime errors that do not require immediate action but should typically be logged and monitored.
warning    : Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
notice     : Normal but significant events.
info       : Interesting events. Examples: User logs in, SQL logs, Application Benchmarks.
debug      : Detailed debug information.\33[0m\n";
            exit;
        }
        global $c;

        if ($level == 'tasks') {
            $path = str_replace('/', DS, trim($c['config']['log']['path']['task'], '/'));
        } else {
            $path = str_replace('/', DS, trim($c['config']['log']['path']['app'], '/'));
        }
        if (strpos($path, 'data') === 0) {  // replace "data" word to application data path
            $file = str_replace('data', DS . trim(DATA, DS), $path);
        }
        if ($level == '' || $level == 'tasks') {
            $this->_displayLogo();
            $this->_follow($file); // Display the debugging.
        } else {
            $this->_displayLogo();
            $this->_follow($file, $level);
        }
    }
);

$app->func(
    '_displayLogo',
    function () {
        echo "\33[1;36m".'
        ______  _            _  _
       |  __  || |__  _   _ | || | ____
       | |  | ||  _ || | | || || ||  _ |
       | |__| || |_||| |_| || || || |_||
       |______||____||_____||_||_||____|

        Welcome to Log Manager v2.0 (c) 2014
You are displaying the log file. To filter log data run "$php task log $level"'."\n\033[0m";

    }
);

/**
 * Console log 
 * Print colorful log messages to your console.
 * @param  $file
 */ 
$app->func(
    '_follow',
    function ($file, $level = '') {
        
        echo "\33[0;36mFollowing log data ...\33[0m\n";

        $break = "\n------------------------------------------------------------------------------------------";

        static $lines = array();
        $size = 0;
        while (true) {

            clearstatcache(); // clear the cache
            if ( ! file_exists($file)) { // start process when file exists.
                continue;
            }
            $currentSize = filesize($file); // continue the process when file size change.
            if ($size == $currentSize) {
                usleep(50);
                continue;
            }
            if ( ! $fh = fopen($file, 'rb')) {
                echo("\n\n\033[1;31mPermission Error: You need to have root access or log folder has not got write permission.\033[0m\n");
                die;
            }
            fseek($fh, $size);
            $i = 0;
            while ($line = fgets($fh)) {
                if ($i == 0) {
                    $line = str_replace("\n", '', $line);
                }
                $line = trim(preg_replace('/[\r\n]/', "\n", $line), "\n"); // remove all newlines   
                $out  = explode('.', $line);

                if (isset($out[1])) {
                    $messageBody = $out[1];
                }
                if (isset($messageBody)) {

                    if (strpos($messageBody, '$_SQL') !== false) {   // remove unnecessary spaces from sql output
                        $line = "\033[1;32m".preg_replace('/[\s]+/', ' ', $line)."\033[0m";
                        $line = preg_replace('/[\r\n]/', "\n", $line);
                    }
                    if (strpos($messageBody, '$_') !== false) {
                        $line = preg_replace('/\s+/', ' ', $line);
                        $line = preg_replace('/\[/', "[", $line);  // do some cleaning

                        if (strpos($messageBody, '$_REQUEST_URI') !== false) {
                            $line  = "\033[1;36m".$break."\n".$line.$break."\n\033[0m";
                        } elseif (strpos($messageBody, '$_LAYER') !== false) {
                            $line = "\033[1;34m".strip_tags($line)."\033[0m";
                        } else {
                            $line = "\033[1;35m".$line."\033[0m";
                        }
                    }
                    if (strpos($messageBody, '$_TASK') !== false) {
                        $line = "\033[1;34m".$line."\033[0m";
                    }
                    if (strpos($messageBody, 'loaded:') !== false) {
                        $line = "\033[0;35m".$line."\033[0m";
                    }
                    if (strpos($messageBody, 'debug') !== false) {   // Do not write two times
                        if ($level == '' OR $level == 'debug') {
                            if (strpos($messageBody, 'Final output sent to browser') !== false) {
                                $line = "\033[1;36m".$line."\033[0m";
                            }
                            $line = "\033[0;35m".$line."\033[0m";
                            if ( ! isset($lines[$i])) {
                                echo $line."\n";
                            }
                        }
                    }
                    if (strpos($messageBody, 'info') !== false) {
                        if ($level == '' OR $level == 'info') {
                            $line = "\033[1;33m".$line."\033[0m";
                            if ( ! isset($lines[$line])) {
                                echo $line."\n";
                            }
                        }
                    }
                    if (strpos($messageBody, 'error') !== false) {
                        if ($level == '' OR $level == 'error') {
                            $line = "\033[1;31m".$line."\033[0m";
                            if ( ! isset($lines[$line])) {
                                echo $line."\n";
                            }
                        }
                    }
                    if (strpos($messageBody, 'alert') !== false) {
                        if ($level == '' OR $level == 'alert') {
                            $line = "\033[1;31m".$line."\033[0m";
                            if ( ! isset($lines[$line])) {
                                echo $line."\n";
                            }
                        }
                    }
                    if (strpos($messageBody, 'emergency') !== false) {
                        if ($level == '' OR $level == 'emergency') {
                            $line = "\033[1;31m".$line."\033[0m";
                            if ( ! isset($lines[$line])) {
                                echo $line."\n";
                            }
                        }
                    }
                    if (strpos($messageBody, 'critical') !== false) {
                        if ($level == '' OR $level == 'critical') {
                            $line = "\033[1;31m".$line."\033[0m";
                            if ( ! isset($lines[$line])) {
                                echo $line."\n";
                            }
                        }
                    }
                    if (strpos($messageBody, 'warning') !== false) {
                        if ($level == '' OR $level == 'warning') {
                            $line = "\033[1;31m".$line."\033[0m";
                            if ( ! isset($lines[$line])) {
                                echo $line."\n";
                            }
                        }
                    }
                    if (strpos($messageBody, 'notice') !== false) {
                        if ($level == '' OR $level == 'notice') {
                            $line = "\033[1;35m".$line."\033[0m";   // 1;44
                            if ( ! isset($lines[$line])) {
                                echo $line."\n";
                            }
                        }
                    }

                } // end isset

                $i++;
                $lines[$line] = $i;
            }
            fclose($fh);
            clearstatcache();
            $size = $currentSize;
        }
    }
);

// Terminal Colour Codes.
/*

$BLACK="33[0;30m";
$DARKGRAY="33[1;30m";
$BLUE="33[0;34m";
$LIGHTBLUE="33[1;34m";
$MAGENTA="33[0;35m";
$CYAN="33[0;36m";
$LIGHTCYAN="33[1;36m";
$RED="33[0;31m";
$LIGHTRED="33[1;31m";
$GREEN="33[0;32m";
$LIGHTGREEN="33[1;32m";
$PURPLE="33[0;35m";
$LIGHTPURPLE="33[1;35m";
$BROWN="33[0;33m";
$YELLOW="33[1;33m";
$LIGHTGRAY="33[0;37m";
$WHITE="33[1;37m";
*/

/* End of file log.php */
/* Location: .app/tasks/controller/log.php */