<?php
defined('STDIN') or die('Access Denied');

Class Log extends Controller {
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function index($level = '')
    {
        if($level == '')
        {
        echo "\33[0;36m".'
        _____      ________     __     __  __        __          _______
      / ___  /    / ____   \   / /    / / / /       / /         / ___   /
    /  /   /  /  / /____/  /  / /    / / / /       / /        /  /   /  /
   /  /   /  /  / _____  /   / /    / / / /       / /        /  /   /  /
  /  /___/  /  / /____/  \  / /____/ / / /____   / /_____   /  /__ /  /
  /_______/   /__________/ /________/ /_______/ /_______ /  /_______/ 
  
                       Welcome to Log Manager (c) 2013
Display logs [$php task log] or to filter logs [$php task log level debug | error | info] '."\033[0m";
            
            // Start the Debugging Task
            $this->_follow(APP .'logs'. DS .'log-'.date('Y-m-d').'.php');
        } 
        else 
        {
            $this->_follow(APP .'logs'. DS .'log-'.date('Y-m-d').'.php', $level);
        }
    }
    
    /**
     * Set level of debug filter.
     * @param string $level
     */
    public function level($level = 'all')
    {
        $this->index($level);
    }
    
    /**
     * CONSOLE LOG
     * Print colorful log messages to your console. 
     * 
     */ 
    private function _follow($file, $level = '')
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
                $line = str_replace('<?php defined(\'BASE\') or die(\'Access Denied\') ?>','',$line);
                
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
            
            static $logged = array();
            
            $date = date('Y-m-d H:i:s');
            
            if( ! isset($logged[$date]))
            {
                // $this->_compile_benchmark();
                $this->_compile_loaded_files();
            }
            
            $logged[$date] = 1;
        }
    }
    
    /**
     *  BENCHMARK INFO 
     */
    private function _compile_loaded_files()
    {
        $config_files = array();
        foreach(Config::getInstance()->is_loaded as $config_file)
        {
            $config_files[] = error_secure_path($config_file);
        }
        
        $lang_files   = array();
        foreach(Locale::getInstance()->is_loaded as $lang_file)
        { 
            $lang_files[]   = error_secure_path($lang_file); 
        }

        $helpers = array();
        foreach(loader::$_helpers as $helper)
        { 
            $helpers[]      = error_secure_path($helper);
        }
        
        $models  = array();
        foreach(loader::$_models as $mod)
        {
            $models[]       = error_secure_path($mod);
        }
              
        $databases = array();
        foreach(loader::$_databases as $db_var)
        { 
            $databases[]    = $db_var;
        }
        
        $output = "\n\33[0;36m********************* LOADED FILES *********************";
        $output.= "\n*";
        if(count($config_files) > 0)
        $output .= "\n* Configs  --> ".implode(',',$config_files);
        if(count($lang_files) > 0)
        $output .= "\n* Locales  --> ".implode(',', $lang_files);
        if(count($models) > 0)
        $output .= "\n* Models   --> ".implode(',',$models);
        if(count($databases) > 0)
        $output .= "\n* Dbs      --> ".implode(',',$databases);
        if(count($helpers) > 0)
        $output .= "\n* Helpers  --> ".implode(',',$helpers);
        $output.= "\n*";
        $output .= "\n********************************************************\n";
        $output .= "\033[0m";
        
        echo $output;
    }
}

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
/* Location: .modules/tasks/log.php */