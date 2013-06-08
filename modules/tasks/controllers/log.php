<?php
defined('CMD') or exit('Access Denied!');

Class Log extends Controller {
    
    function __construct()
    {   
        parent::__construct();
    }         
    
    public function index($level = '')
    {   
        if($level == '')
        {
        echo '
        _____      ________     __     __  __        __          _______
      / ___  /    / ____   \   / /    / / / /       / /         / ___   /
    /  /   /  /  / /____/  /  / /    / / / /       / /        /  /   /  /
   /  /   /  /  / _____  /   / /    / / / /       / /        /  /   /  /
  /  /___/  /  / /____/  \  / /____/ / / /____   / /_____   /  /__ /  /
  /_______/   /__________/ /________/ /_______/ /_______ /  /_______/ 
  
                       Welcome to Log Manager (c) 2013
 Log Debug default all to set a level run '."\033[34m".'[$php task.php log level debug]'."\033[0m".' levels'."\033[34m".' debug | error | info '."\033[0m\n\n";
            
            // Start the Debugging
            $this->_follow(APP.'core'. DS .'logs'. DS .'log-'.date('Y-m-d').'.php');
        }
    }
    
    public function level($level = 'all')
    {
        $this->index($level);
    }
    
    /**
     * CONSOLE LOG
     * Print colorful log messages to your console. 
     * 
     */ 
    private function _follow($file)
    {        
        $size = 0;
        while (true)
        {
            clearstatcache(); // clear the cache
            if( ! file_exists($file)){  // start process when file exists.
                continue;
            }
            $currentSize = filesize($file); // continue the process when file size change.
            if ($size == $currentSize){
                usleep(100);
                continue;
            }

            $fh = fopen($file, "r");
            fseek($fh, $size);

            $i=0;
            while ($line = fgets($fh))
            {
                if($i == 0) $line = str_replace("\n",'',$line);
                $line = str_replace('<?php defined(\'BASE\') or exit(\'Access Denied!\'); ?>','',$line);
                
                // remove all newlines
                $line = trim(preg_replace('/[\r\n]/', ' ', $line), "\n");
                $line = str_replace('[@]', "\n", $line); // new line
                $out  = explode(" ",$line);
                // echo print_r($out, true)."\n\n";
                
                if(isset($out[5]))
                {
                    if(strpos($out[5], '[') !== FALSE)  // module logs.
                    {
                        $line = "\033[0;33m".$line."\033[0m";
                    }               
                    if(strpos($out[5],'SQL') !== FALSE)
                    {
                        $line = "\033[0;32m".$line."\033[0m";
                    }
                    if(strpos($out[5], 'Task') !== FALSE)
                    {
                        $line = "\033[0;36m".$line."\033[0m";
                    }
                    /*
                    if(strpos($out[5], 'Hmvc') !== FALSE && isset($out[6]) && strpos($out[6], 'Request') !== FALSE)
                    {
                        $line = "\033[0;34m".$line."\033[0m";
                    }
                    if(strpos($out[5], 'Hmvc') !== FALSE && isset($out[6]) && strpos($out[6], 'Output') !== FALSE)
                    {
                        $line = "\033[0;34m".$line."\033[0m";
                    }
                     */
                }

     
                if(strpos($line, 'DEBUG') !== FALSE)
                {
                    $line = "\033[0;35m".$line."\033[0m";
                }
                if(strpos($line, 'ERROR') !== FALSE)
                {
                    $line = "\033[0;31m".$line."\033[0m";
                }
                if(strpos($line, 'INFO') !== FALSE)
                {
                    $line = "\033[0;34m".$line."\033[0m";
                }
                if(strpos($line, 'BENCH') !== FALSE)
                {
                    $line = "\033[0;36m".$line."\033[0m";
                }
  
                echo $line."\n";
                $i++;
            }
            
            fclose($fh);
            $size = $currentSize;
            
            $this->_benchmark();
        }
       
    }
    
    /**
     * Clear Logs 
     */
    function clear()
    {
        $clear_sh = "PROJECT_DIR=\${PWD}

        if [ ! -d obullo ]; then
            # Check the obullo directory exists, so we know you are in the project folder.
            echo \"You must be in the project folder root ! Try cd /your/www/path/projectname\".
            return
        fi

        # define your paths.
        APP_LOG_DIR=\"\$PROJECT_DIR/app/core/logs/\"

        # delete application and module directory log files.
        # help https://help.ubuntu.com/community/find
        find \$APP_LOG_DIR -name 'log-*.php' -exec rm -rf {} \;
        echo \"\33[0;32mGreat, all log files successfully deleted !\33[0m\"";
        
        echo shell_exec($clear_sh);
    }
    
    function _benchmark()
    {
        static $logged = array();
        
        if (function_exists('memory_get_usage') && ($usage = memory_get_usage()) != '')
        {
            $memory_usage = number_format($usage)." bytes";
        }
        else
        {
            $memory_usage = "memory_get_usage() function not found on your php configuration.";
        }
        
        $bench = lib('ob/Benchmark'); // init to bencmark for profiling.
        
        $profile = array();
        foreach ($bench->marker as $key => $val)
        {
            // We match the "end" marker so that the list ends
            // up in the order that it was defined
            if (preg_match("/(.+?)_end/i", $key, $match))
            {             
                if (isset($bench->marker[$match[1].'_end']) AND isset($bench->marker[$match[1].'_start']))
                {
                    $profile[$match[1]] = benchmark_elapsed_time($match[1].'_start', $key);
                }
            }
        }
        foreach ($profile as $key => $val)
        {
            $key = ucwords(str_replace(array('_', '-'), ' ', $key));

            $date = date('Y-m-d H:i:s');
            
            if( ! isset($logged[$date]))
            {
                echo "\033[0;36mBENCH - ".$date." --> $key - $val\033[0m\n";
                echo "\033[0;36mBENCH - ".$date." --> Memory Usage: $memory_usage\033[0m\n";
            }
            
            $logged[$date] = 1;
        }
    }
}

//Terminal Colour Codes (BASH CODES)
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
/* Location: .modules/tasks/log.php */