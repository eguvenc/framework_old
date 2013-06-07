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
 Please run '."\033[34m".'[$php task.php log level all]'."\033[0m".' log levels'."\033[34m".' all | debug | error | info '."\033[0m\n\n";
        } 
        else 
        {
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
                    if(strpos($out[5], 'Hmvc') !== FALSE && isset($out[6]) && strpos($out[6], 'Request') !== FALSE)
                    {
                        $line = "\033[0;34m".$line."\033[0m";
                    }
                    if(strpos($out[5], 'Hmvc') !== FALSE && isset($out[6]) && strpos($out[6], 'Output') !== FALSE)
                    {
                        $line = "\033[0;34m".$line."\033[0m";
                    }
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

                echo $line."\n";
                $i++;
            }
            fclose($fh);
            $size = $currentSize;
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