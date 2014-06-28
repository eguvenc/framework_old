<?php

defined('STDIN') or die('Access Denied');

/**
 * $c clear
 * 
 * @var Controller
 */
$app = new Controller;

$app->func(
    'index',
    function () {
        $this->_clear();  // Start the Clear Task
    }
);

$app->func(
    '_clear',
    function () use ($c) {

        $file = trim($c->load('config')['log']['path']['app'], '/');
        $file = str_replace('/', DS, $file);

        if (strpos($file, 'data') === 0) { 
            $file = str_replace('data', rtrim(DATA, DS), $file);
        } 
        $exp      = explode(DS, $file);
        $filename = array_pop($exp);
        $path     = implode(DS, $exp). DS;

        if ( ! is_file($path.$filename)) {
            echo "\33[1;31mApplication log file does not exists.\33[0m\n";
            exit;
        }

        $clear_sh = "
        PROJECT_DIR=\${PWD}

        if [ ! -d ".OBULLO." ]; then
            # Check the OBULLO directory is exists, so we know you are in the project folder.
            echo \"You must be in the project root ! Try cd /your/www/path/projectname\".
            return
        fi

        # define your paths.
        APP_LOG_DIR=\"".$path."\"

        # delete app directory log files.
        # help https://help.ubuntu.com/community/find

        find \$APP_LOG_DIR -name '".$filename."' -exec rm -rf {} \;
        echo \"\33[0m\33[1;36mApplication log files deleted.\33[0m\";";
        
        echo shell_exec($clear_sh);
    }
);

/* End of file clear.php */
/* Location: .app/tasks/controller/clear.php */