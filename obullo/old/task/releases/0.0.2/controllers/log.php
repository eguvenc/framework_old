<?php
defined('STDIN') or die('Access Denied');

error_reporting(E_ERROR | E_WARNING | E_PARSE); // error reporting

function logErrorHandler($errno, $errstr, $errfile, $errline)
{
    echo("\n\033[1;33mError: $errstr \033[0m"); // Do something other than output message.
}

set_error_handler('logErrorHandler');

// ------------------------------------------------------------------------

/**
 * $c log
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
});

$c->func('index', function($level = ''){

    if($level == '')
    {
        $this->_displayLogo();
        $this->_follow(DATA .'logs'. DS .'log-'.date('Y-m-d').'.php'); // Display the debugging.
    } 
    else 
    {
        $this->_follow(DATA .'logs'. DS .'log-'.date('Y-m-d').'.php', $level);
    }

});
    
// ------------------------------------------------------------------------

$c->func('_displayLogo', function(){
echo "\33[1;36m".'
        ______  _            _  _
       |  __  || |__  _   _ | || | ____
       | |  | ||  _ || | | || || ||  _ |
       | |__| || |_||| |_| || || || |_||
       |______||____||_____||_||_||____|

        Welcome to Log Manager v2.0 (c) 2014
Display logs [$php task log], to filter logs [$php task log index $level]'."\n\033[0m";
});

// ------------------------------------------------------------------------

/**
 * Console log 
 * Print colorful log messages to your console.
 * @param  $file
 */ 
$c->func('_follow', function($file, $level = ''){
 
    static $lines = array();

    $size = 0;
    while (true)
    {
        clearstatcache(); // clear the cache
        if( ! file_exists($file)) // start process when file exists.
        {  
            continue;
        }

        $currentSize = filesize($file); // continue the process when file size change.
        if ($size == $currentSize){
            usleep(50);
            continue;
        }

        if( ! $fh = fopen($file, 'rb'))
        {
            echo("\n\n\033[1;31mPermission Error: You need root access or log folder has not got write permission.\033[0m\n"); exit;
        } 

        fseek($fh, $size);

        $i = 0;
        while ($line = fgets($fh))
        {
            if($i == 0) 
            {
                $line = str_replace("\n",'',$line);
            }

            $line = str_replace('<?php defined(\'ROOT\') or die(\'Access Denied\') ?>','',$line);
            
            // remove all newlines
            $line = trim(preg_replace('/[\r\n]/', ' ', $line), "\n");
            $line = str_replace('[@]', "\n", $line); // new line
            $out  = explode(" ",$line);  // echo print_r($out, true)."\n\n";
            
            if($level == '' OR $level == 'debug')
            {
                if(isset($out[5]))
                {
                    if(strpos($out[5], '[') !== false)  // colorful logs.
                    {
                        $line = "\033[0;33m".$line."\033[0m";
                    }
                    if(strpos($out[5],'SQL') !== false)  // remove unnecessary spaces from sql output
                    {
                        $line = str_replace('SQL: ','', "\033[1;32m".preg_replace('/\s+/', ' ', $line)."\033[0m");
                    }
                    if(strpos($out[5],'$_') !== false)
                    {
                        $line = preg_replace('/\s+/', ' ', $line);
                        $line = preg_replace('/\[/', "\n[", $line);

                        if(strpos($out[5],'$_REQUEST_URI') !== false)
                        {
                            $break = "\n------------------------------------------------------------------------------------------";
                            $line = "\033[1;36m".$break."\n".$line.$break."\n\033[0m";
                        } 
                        else 
                        {
                            $line = "\033[1;35m".$line."\033[0m";
                        }

                    }                  
                    if(strpos($out[5], 'Task') !== false)
                    {
                        $line = "\033[0;35m".$line."\033[0m";
                    }
                    if(isset($out[7]) AND strpos($out[7], 'loaded:') !== false)
                    {
                        $line = "\033[0;35m".$line."\033[0m";
                    }
                }
            }
 
            if(strpos($line, 'DEBUG') !== false)  // Do not write two times
            {
                if($level == '' OR $level == 'debug')
                {
                    $line = "\033[0;35m".$line."\033[0m";
                    if( ! isset($lines[$line]))
                    {
                        echo $line."\n";
                    }
                }
            }

            if(strpos($line, 'ERROR') !== false)
            {
                if($level == '' OR $level == 'error')
                {
                    $line = "\033[1;31m".$line."\033[0m";
                    if( ! isset($lines[$line]))
                    {
                        echo $line."\n";
                    }
                }
            }

            if(strpos($line, 'INFO') !== false)
            {
                if($level == '' OR $level == 'info')
                {
                    $line = "\033[1;35m".$line."\033[0m";
                    if( ! isset($lines[$line]))
                    {
                        echo $line."\n";
                    }
                }
            }

            if(strpos($line, 'BENCH') !== false)
            {
                if($level == '' OR $level == 'bench')
                {
                    $line = "\033[1;36m".$line."\033[0m";
                    if( ! isset($lines[$line]))
                    {
                        echo $line."\n";
                    }
                }
            }
            $i++;
            $lines[$line] = $line;
        }
        
        fclose($fh);
        clearstatcache();
        $size = $currentSize;
    }
});

// Terminal Colour Codes ( TERMINAL SCREEN BASH CODES )
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