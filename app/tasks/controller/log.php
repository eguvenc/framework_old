<?php
defined('STDIN') or die('Access Denied');

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
        $this->_follow(APP .'logs'. DS .'log-'.date('Y-m-d').'.php'); // Display the debugging.
    } 
    else 
    {
        $this->_follow(APP .'logs'. DS .'log-'.date('Y-m-d').'.php', $level);
    }
});
    
// ------------------------------------------------------------------------

$c->func('_displayLogo', function(){
echo "\33[0;36m".'
        ______  _            _  _
       |  __  || |__  _   _ | || | ____
       | |  | ||  _ || | | || || ||  _ |
       | |__| || |_||| |_| || || || |_||
       |______||____||_____||_||_||____|

        Welcome to Log Manager (c) 2013
Display logs [$php task log], to filter logs [$php task log $level]'."\n\033[0m";
});

// ------------------------------------------------------------------------

/**
 * Set level of debug filter.
 * @param string $level
 */   
$c->func('level', function($level = 'all'){
    $this->index($level);
});

// ------------------------------------------------------------------------

/**
 * Console log 
 * Print colorful log messages to your console.
 * @param  $file
 */ 
$c->func('_follow', function($file, $level = ''){
    
    $size = 0;
    while (true)
    {
        clearstatcache(); // clear the cache
        if( ! file_exists($file)) // start process when file exists.
        {  
            continue;
        }
        $currentSize = filesize($file); // continue the process when file size change.
        if ($size == $currentSize)
        {
            usleep(100);
            continue;
        }

        $fh = fopen($file, "r");
        fseek($fh, $size);

        $i=0;
        while ($line = fgets($fh))
        {
            if($i == 0) $line = str_replace("\n",'',$line);
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
                    if(strpos($out[5],'SQL') !== false)
                    {
                        $line = "\033[0;32m".$line."\033[0m";
                    }
                    if(strpos($out[5], 'Task') !== false)
                    {
                        $line = "\033[0;36m".$line."\033[0m";
                    }
                }
            }
 
            if(strpos($line, 'DEBUG') !== false)
            {
                if($level == '' OR $level == 'debug')
                {
                    $line = "\033[0;35m".$line."\033[0m";
                    echo $line."\n";
                }
            }
            if(strpos($line, 'ERROR') !== false)
            {
                if($level == '' OR $level == 'error')
                {
                    $line = "\033[0;31m".$line."\033[0m";
                    echo $line."\n";
                }
            }
            if(strpos($line, 'INFO') !== false)
            {
                if($level == '' OR $level == 'info')
                {
                    $line = "\033[0;34m".$line."\033[0m";
                    echo $line."\n";
                }
            }
            if(strpos($line, 'BENCH') !== false)
            {
                if($level == '' OR $level == 'bench')
                {
                    $line = "\033[0;36m".$line."\033[0m";
                    echo $line."\n";
                }
            }

            $i++;
        }
        
        fclose($fh);
        $size = $currentSize;
        
        static $logged = array();
        
        $date = date('Y-m-d H:i:s');
        
        if( ! isset($logged[$date]))
        {
            $this->_compileFiles();
        }
        
        $logged[$date] = 1;
    }
});

// ------------------------------------------------------------------------
    
$c->func('_compileFiles', function(){

    $error = new Error;
    
    $configs = array();
    foreach(getComponentInstance('config')->is_loaded as $cfg)
    {
        $configs[] = $error->getSecurePath($cfg);
    }
    
    $locales = array();
    foreach(getComponentInstance('lingo')->is_loaded as $lcl)
    { 
        $locales[] = $error->getSecurePath($lcl); 
    }
    
    if(count($configs) > 0 || count($locales) > 0)
    {
        $output  = "\33[0;36m________LOADED FILES______________________________________________________";
        $output .= "\n";
        
        if(count($configs) > 0)
        {
            $output .= "\nConfigs   --> ".implode(', ',$configs);
        }
        
        if(count($locales) > 0)
        {
            $output .= "\nLingo   --> ".implode(', ',$locales);    
        }

        $output .= "\n";
        $output .= "__________________________________________________________________________";
        $output .= "\n\n";
        $output .= "\033[0m";
        
        echo $output;
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