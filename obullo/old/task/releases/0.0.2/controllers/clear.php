<?php
defined('STDIN') or die('Access Denied');

/**
 * $c clear
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
});

$c->func('index', function(){
    $this->_clear();  // Start the Clear Task
});

$c->func('_clear', function(){

    $clear_sh = "
    ####################
    #  CLEAR. S H  ( Clear all application log files )
    ####################

    PROJECT_DIR=\${PWD}

    if [ ! -d ".PACKAGES." ]; then
        # Check the PACKAGES directory exists, so we know you are in the project folder.
        echo \"You must be in the project root ! Try cd /your/www/path/projectname\".
        return
    fi

    # define your paths.
    APP_LOG_DIR=\"".DATA."/logs/\"

    # delete app directory log files.
    # help https://help.ubuntu.com/community/find
    find \$APP_LOG_DIR -name 'log-*.php' -exec rm -rf {} \;
    echo \"\33[0m\33[1;36mAll log files deleted.\33[0m\";";
    
    echo shell_exec($clear_sh);

});

/* End of file clear.php */
/* Location: .app/tasks/controller/clear.php */